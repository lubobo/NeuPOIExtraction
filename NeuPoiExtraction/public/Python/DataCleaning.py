#-*-coding:utf-8-*-
import csv
import pandas as pd
from datetime import datetime
from urllib import request
import json
import math

a = 6378245.0
ee = 0.00669342162296594323
x_pi = 3.14159265358979324 * 3000.0 / 180.0
#转换经度
def transformLat(lat,lon):
    ret = -100.0 + 2.0 * lat + 3.0 * lon + 0.2 * lon * lon + 0.1 * lat * lon + 0.2 * math.sqrt(abs(lat))
    ret += (20.0 * math.sin(6.0 * lat * math.pi) + 20.0 * math.sin(2.0 * lat * math.pi)) * 2.0 / 3.0
    ret += (20.0 * math.sin(lon * math.pi) + 40.0 * math.sin(lon / 3.0 * math.pi)) * 2.0 / 3.0
    ret += (160.0 * math.sin(lon / 12.0 * math.pi) + 320 * math.sin(lon * math.pi / 30.0)) * 2.0 / 3.0
    return ret
#转换纬度
def transformLon(lat,lon):
    ret = 300.0 + lat + 2.0 * lon + 0.1 * lat * lat + 0.1 * lat * lon + 0.1 * math.sqrt(abs(lat))
    ret += (20.0 * math.sin(6.0 * lat * math.pi) + 20.0 * math.sin(2.0 * lat * math.pi)) * 2.0 / 3.0
    ret += (20.0 * math.sin(lat * math.pi) + 40.0 * math.sin(lat / 3.0 * math.pi)) * 2.0 / 3.0
    ret += (150.0 * math.sin(lat / 12.0 * math.pi) + 300.0 * math.sin(lat / 30.0 * math.pi)) * 2.0 / 3.0
    return ret
#Wgs transform to gcj
def wgs2gcj(lat,lon):
    dLat = transformLat(lon - 105.0, lat - 35.0)
    dLon = transformLon(lon - 105.0, lat - 35.0)
    radLat = lat / 180.0 * math.pi
    magic = math.sin(radLat)
    magic = 1 - ee * magic * magic
    sqrtMagic = math.sqrt(magic)
    dLat = (dLat * 180.0) / ((a * (1 - ee)) / (magic * sqrtMagic) * math.pi)
    dLon = (dLon * 180.0) / (a / sqrtMagic * math.cos(radLat) * math.pi)
    mgLat = lat + dLat
    mgLon = lon + dLon
    loc = [mgLat, mgLon]
    return loc
#gcj transform to bd2
def gcj2bd(lat,lon):
    x=lon
    y = lat
    z = math.sqrt(x * x + y * y) + 0.00002 * math.sin(y * x_pi)
    theta = math.atan2(y, x) + 0.000003 * math.cos(x * x_pi)
    bd_lon = z * math.cos(theta) + 0.0065
    bd_lat = z * math.sin(theta) + 0.006
    bdpoint = [bd_lat, bd_lon]
    return bdpoint
#wgs transform to bd
def wgs2bd(lat,lon):
    wgs_to_gcj = wgs2gcj(lat, lon)
    gcj_to_bd = gcj2bd(wgs_to_gcj[0], wgs_to_gcj[1])
    return gcj_to_bd


def dis_temp_data(data,temp):
    df = pd.read_csv(data, encoding="GBK", usecols=['编号', '目标ID', 'UTC时间', '经度', '纬度', '速度', '方向', '报警', '状态', '其他信息', '计费标识', 'GPS数据', '记录时间'])
    df = pd.DataFrame(df)
    temp_buff = df.values.tolist()
    temp_buff.sort(key=lambda x: (x[1], x[2]))
    with open(temp, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['编号', '目标ID', 'UTC时间', '经度', '纬度', '速度', '方向',
                        '报警', '状态', '其他信息', '计费标识', 'GPS数据', '记录时间'])
        datafile.close()

    count = 0
    with open(temp, 'a+', newline='') as datafile:
        write = csv.writer(datafile)
        for i in range(len(temp_buff) - 1):
            if temp_buff[i][8] == 0 or temp_buff[i][8] == 262144:
                if temp_buff[i][1] == temp_buff[i + 1][1] \
                        and temp_buff[i][3] == temp_buff[i + 1][3] \
                        and temp_buff[i][4] == temp_buff[i + 1][4]:
                    continue
                else:
                    temp_buff[i][3] = float(temp_buff[i][3]) / 1000000
                    temp_buff[i][4] = float(temp_buff[i][4]) / 1000000
                    temp_buff[i][2] = datetime.fromtimestamp(int(temp_buff[i][2]))
                    write.writerow(temp_buff[i])
                    count += 1
    print(count)

def dis_taxi(temp, taxi):
    with open(taxi, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['编号', '目标ID', '上车时间', '上车经度', '上车纬度', '下车时间', '下车经度', '下车纬度', '时间间隔'])
    df = pd.read_csv(temp, encoding="GBK", usecols=['编号', '目标ID', 'UTC时间', '经度', '纬度', '状态', '记录时间'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any')
    temp_list = list(df.groupby('目标ID'))
    flag = False
    dis_time = 0
    count = 0
    for t_i in range(len(temp_list)):
        data_list = temp_list[t_i][1].values.tolist()
        temp_len = len(data_list)
        for d_i in range(temp_len - 1):
            if data_list[d_i][5] == 262144 and data_list[d_i+1][5] == 262144:
                if data_list[d_i-1][5] == 0:
                    bd = wgs2bd(data_list[d_i][3], data_list[d_i][4])
                    data_list[d_i][3] = bd[0]
                    data_list[d_i][4] = bd[1]

                    temp_data = data_list[d_i][0:5]
                    start_time = data_list[d_i][6]
                    for i in range(d_i, temp_len-1):
                        if data_list[i][5] == 0 and data_list[i-1][5] == 262144:
                            bd = wgs2bd(data_list[d_i][3], data_list[d_i][4])
                            data_list[d_i][3] = bd[0]
                            data_list[d_i][4] = bd[1]

                            temp_data += data_list[i][2:5]
                            dis_time = data_list[i][6]-start_time
                            temp_data.append(dis_time)
                            flag = True
                            break
                        else:
                            flag = False
                    if flag:
                        if dis_time>60 and dis_time<43200:
                            with open(taxi, 'a+', newline='') as datafile:
                                write = csv.writer(datafile)
                                write.writerow(temp_data)
                                count += 1
    print(count)
