#-*-coding:utf-8-*-
import csv
import re
from urllib.parse import quote
import pandas as pd
from sklearn.cluster import MiniBatchKMeans
import matplotlib.pyplot as plt
from urllib import request
import json
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer,encoding='gb18030') #改变标准输出的默认编码

def MiniBatchKMeans_data (temp_file,taxi_file):
    df = pd.read_csv(temp_file, encoding='GBK', usecols=['上车经度', '上车纬度', '下车经度', '下车纬度'])
    pd.DataFrame(df)

    temp_df = df['上车经度'].values.tolist()
    temp_df1 = df['下车经度'].values.tolist()
    lon_df = temp_df + temp_df1

    df_temp = df['上车纬度'].values.tolist()
    df_temp1 = df['下车纬度'].values.tolist()
    res_df = df_temp + df_temp1

    df = pd.read_csv(temp_file, encoding="GBK", usecols=['目标ID', '上车经度', '上车纬度', '下车经度', '下车纬度'])
    pd.DataFrame(df)
    temp_df = df.values.tolist()
    data_list = []
    for d_i in range(len(temp_df)):
        temp_list = temp_df[d_i][1:3]
        data_list.append(temp_list)
        temp_list_1 = temp_df[d_i][3:5]
        data_list.append(temp_list_1)

    lon = []
    res = []
    lon_res = []
    temp = []
    km = MiniBatchKMeans(n_clusters=2000, init_size=6000)
    lable = km.fit(data_list)
    for i in range(len(lable.cluster_centers_)):
        lon.append(lable.cluster_centers_[i][0])
        res.append(lable.cluster_centers_[i][1])
        temp.append(lable.cluster_centers_[i][0])
        temp.append(lable.cluster_centers_[i][1])
        lon_res.append(temp)
        temp = []

    with open(taxi_file, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['经度', '纬度', '地址'])

    count = 0
    for d_i in range(len(lon_res)):
        temp_response = request.urlopen(
            'http://api.map.baidu.com/geocoder/v2/?'
            'location=' + str(lon_res[d_i][1]) + ',' + str(lon_res[d_i][0]) +
            '&output=json&pois=0&ak=b9rz6aC5DUskA0NqRZfmPWC04Fgb7ti8')
        page_temp = temp_response.read()
        page_temp = page_temp.decode('utf-8')
        res = json.loads(page_temp)
        temp_data = lon_res[d_i][0:2]
        with open(taxi_file, 'a+', newline='') as datafile:
            temp_data.append(res['result']['formatted_address'])
            write = csv.writer(datafile)
            write.writerow(temp_data)
            count += 1
    print(str(count))

def temp_poi(taxi_data, temp_poi):
    df = pd.read_csv(taxi_data, encoding='GBK')
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    temp_list = df.values.tolist()

    with open(temp_poi, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['聚类点经度', '聚类点纬度', '聚类点', '经度', '纬度', '地点名'])

    count = 0
    for d_i in range(len(temp_list)):
        if bool(re.search(r'\d+[\d,号]$', temp_list[d_i][2])):
            temp_str = 'http://api.map.baidu.com/place/v2/suggestion?' \
                       'query=' + str(temp_list[d_i][2]) + '&region=沈阳&' \
                       'location=' + str(temp_list[d_i][1]) + ',' + str(temp_list[d_i][0]) + \
                       '&city_limit=true&output=json&ak=b9rz6aC5DUskA0NqRZfmPWC04Fgb7ti8'
            temp_url = quote(temp_str, safe='/:?=&')
            temp_response = request.urlopen(temp_url)
            page_temp = temp_response.read()
            page_temp = page_temp.decode('utf-8')
            res = json.loads(page_temp)
            if res['message'] == "ok" and 'result' in res:
                temp_data = []
                for i in range(len(res['result'])):
                    if 'location' in res['result'][i]:
                        if 'lng' in res['result'][i]['location'] and \
                                'lat' in res['result'][i]['location']:
                            temp_data.append(temp_list[d_i][0])
                            temp_data.append(temp_list[d_i][1])
                            temp_data.append(temp_list[d_i][2])
                            temp_data.append(res['result'][i]['location']['lng'])
                            temp_data.append(res['result'][i]['location']['lat'])
                            temp_data.append(res['result'][i]['name'])
                            with open(temp_poi, 'a+', newline='') as datafile:
                                write = csv.writer(datafile)
                                write.writerow(temp_data)
                                count += 1
                            temp_data = []
    print(str(count))
