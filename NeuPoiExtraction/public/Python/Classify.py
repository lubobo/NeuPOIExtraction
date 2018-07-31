#-*-coding:utf-8-*-
import csv
import pandas as pd
from collections import Counter
import jieba.analyse
import numpy as np
from sklearn.naive_bayes import MultinomialNB
from urllib import request
import json
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer,encoding='gb18030') #改变标准输出的默认编码

'''
    计算信息增益
'''
class InformationGain:
    def __init__(self, X, y):
        self.X = X
        self.y = y
        self.totalSampleCount = X.shape[0]      # 样本总数
        self.totalSystemEntropy = 0             # 系统总熵
        self.totalClassCountDict = {}           # 存储每个类别的样本数量是多少
        self.nonzeroPosition = X.T.nonzero()    # 将X转置之后输出非零值的位置
        self.igResult = []                      # 保存结果的list
        self.wordExistSampleCount = 0
        self.wordExistClassCountDict = {}
        self.iter()

    # 将结果列表排序输出
    def get_result(self):
        return self.igResult

    # 计算系统总熵
    def cal_total_system_entropy(self):
        # 计算每个类别各有多少个
        for label in self.y:
            if label not in self.totalClassCountDict:
                self.totalClassCountDict[label] = 1
            else:
                self.totalClassCountDict[label] += 1
        for cls in self.totalClassCountDict:
            probs = self.totalClassCountDict[cls] / float(self.totalSampleCount)
            self.totalSystemEntropy -= probs * np.log(probs)

    # 遍历nonzeroPosition时，逐步计算出每个word的信息增益
    def iter(self):
        self.cal_total_system_entropy()

        pre = 0
        for i in range(len(self.nonzeroPosition[0])):
            if i != 0 and self.nonzeroPosition[0][i] != pre:
                for notappear in range(pre+1, self.nonzeroPosition[0][i]):  # 如果一个词在整个样本集中都未出现，则直接赋为0
                    self.igResult.append(0.0)
                ig = self.cal_information_gain()
                self.igResult.append(ig)
                self.wordExistSampleCount = 0
                self.wordExistClassCountDict = {}
                pre = self.nonzeroPosition[0][i]
            self.wordExistSampleCount += 1
            yclass = self.y[self.nonzeroPosition[1][i]]  # 求得当前样本的标签
            if yclass not in self.wordExistClassCountDict:
                self.wordExistClassCountDict[yclass] = 1
            else:
                self.wordExistClassCountDict[yclass] += 1
        # 计算最后一个单词的ig
        ig = self.cal_information_gain()
        self.igResult.append(ig)

    # 计算ig的主要函数
    def cal_information_gain(self):
        x_exist_entropy = 0
        x_nonexist_entropy = 0

        for cls in self.wordExistClassCountDict:
            probs = self.wordExistClassCountDict[cls] / float(self.wordExistSampleCount)
            x_exist_entropy -= probs * np.log(probs)

            probs = (self.totalClassCountDict[cls] - self.wordExistClassCountDict[cls]) / float(self.totalSampleCount - self.wordExistSampleCount)
            if probs == 0: #该单词在每条样本中都出现了，虽然该几率很小
                x_nonexist_entropy = 0
            else:
                x_nonexist_entropy -= probs*np.log(probs)

        for cls in self.totalClassCountDict:
            if cls not in self.wordExistClassCountDict:
                probs = self.totalClassCountDict[cls] / float(self.totalSampleCount - self.wordExistSampleCount)
                x_nonexist_entropy -= probs*np.log(probs)

        # 合并两项，计算出ig
        ig = self.totalSystemEntropy - ((self.wordExistSampleCount/float(self.totalSampleCount))*x_exist_entropy +
                                        ((self.totalSampleCount-self.wordExistSampleCount)/float(self.totalSampleCount)*x_nonexist_entropy))
        return ig

'''
    获取训练集数据
'''
def get_train_data(taxi_data, temp_city_poi):
    df = pd.read_csv(taxi_data, encoding="GBK", usecols=['经度', '纬度'])
    pd.DataFrame(df)
    temp_list = df.values.tolist()

    with open(temp_city_poi, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['地点名', '地址', '经度', '纬度', 'POI类别', 'POI标签'])

    temp_data = []
    count = 0
    for d_i in range(len(temp_list)):
        temp_response = request.urlopen(
            'http://api.map.baidu.com/geocoder/v2/?location='
            + str(temp_list[d_i][1]) + ',' + str(temp_list[d_i][0]) +
            '&output=json&pois=1&ak=b9rz6aC5DUskA0NqRZfmPWC04Fgb7ti8')
        page_temp = temp_response.read()
        page_temp = page_temp.decode('utf-8')
        json_data = json.loads(page_temp)
        if json_data['result']['pois']:
            temp_dir = json_data['result']['pois']
            for i in range(len(temp_dir)):
                count += 1
                temp_data.append(temp_dir[i]['name'])
                temp_data.append(temp_dir[i]['addr'])
                temp_data.append(temp_dir[i]['point']['x'])
                temp_data.append(temp_dir[i]['point']['y'])
                temp_data.append(temp_dir[i]['poiType'])
                temp_data.append(temp_dir[i]['tag'])
                with open(temp_city_poi, 'a+', newline='') as datafile:
                    write = csv.writer(datafile)
                    write.writerow(temp_data)
                temp_data = []
    print(str(count))

'''
    获取poi_id.csv文件：
'''
def get_poi_id(city_poi, city_poi_id):
    df = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    temp_list = list(df.groupby('POI类别'))
    count = 0
    with open(city_poi_id, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['序号', 'poi类别', 'poi个数'])
    temp_poi_id = []
    for i in range(len(temp_list)):
        temp_poi_id.append(i)
        temp_poi_id.append(temp_list[i][0])
        temp_poi_id.append(len(temp_list[i][1]))
        with open(city_poi_id, 'a+', newline='') as datafile:
            if temp_poi_id[0] and temp_poi_id[1] and temp_poi_id[2]:
                write = csv.writer(datafile)
                write.writerow(temp_poi_id)
                count += 1
        temp_poi_id = []
    print(str(count))


'''
    获取poi_key.csv文件：
'''
def get_poi_key(city_poi, city_poi_key, allow_pos):
    df = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(df)
    temp_list_1 = list(df.groupby('POI类别'))
    list_1 = []
    count = 0

    with open(city_poi_key, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['poi类别号', '关键字', '频度'])
    for d_i in range(len(temp_list_1)):
        temp_df_list = temp_list_1[d_i][1]['地点名'].values.tolist()
        for t_i in range(len(temp_df_list)):
            temp_cut = jieba.analyse.extract_tags(str(temp_df_list[t_i]), topK=20, withWeight=False, allowPOS=allow_pos)
            for i in range(len(temp_cut)):
                list_1.append(temp_cut[i])
        if list_1:
            counter = Counter(list_1)
            temp_key = []
            for key in counter:
                temp_key.append(d_i)
                temp_key.append(key)
                temp_key.append(counter[key])
                with open(city_poi_key, 'a+', newline='') as datafile:
                    write = csv.writer(datafile)
                    write.writerow(temp_key)
                    count += 1
                temp_key = []
        list_1 = []
    print(str(count))

'''
    获取key.csv文件：
'''
def get_key(city_poi, key, allow_pos):
    df = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    temp_list = list(df.groupby('POI类别'))
    list_1 = []
    poi_list = []
    for d_i in range(len(temp_list)):
        list_1.append(temp_list[d_i][0])
        temp = temp_list[d_i][1]['地点名'].values.tolist()
        for t_i in range(len(temp)):
            temp_cut = jieba.analyse.extract_tags(str(temp[t_i]), topK=5, withWeight=False, allowPOS=allow_pos)
            for i in range(len(temp_cut)):
                list_1.append(temp_cut[i])
        str_1 = list_1[0]
        temp_df = pd.DataFrame(list_1, columns=[str_1])
        temp_df.duplicated(str_1)
        temp_df.drop_duplicates(str_1, inplace=True)
        temp_df.drop(0, inplace=True)
        last_list = temp_df.values.tolist()
        list_1.clear()
        for i in range(len(last_list)):
            list_1.append(last_list[i][0])
        for i in range(len(list_1)):
            poi_list.append((list_1[i]))
        list_1.clear()

    last_df = pd.DataFrame(poi_list, columns=['poi'])
    last_df.duplicated('poi')
    last_df.drop_duplicates('poi', inplace=True)
    poi_list = []
    sumOfword = 0
    with open(key, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['关键字', '可用性'])
    for i in range(len(last_df)):
        poi_list.append(last_df.iloc[i]['poi'])
        poi_list.append(1)
        with open(key, 'a+', newline='') as datafile:
            write = csv.writer(datafile)
            write.writerow(poi_list)
        poi_list.clear()
        sumOfword += 1
    print((sumOfword))

'''
    训练朴素贝叶斯分类：
'''
def test_multinomialNB_classify(city_poi, city_poi_id, key, test_city_poi, test_len, allow_pos):
    df = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    temp_list = df.values.tolist()

    temp_key = []
    kf = pd.read_csv(key, encoding='GBK', usecols=['关键字'])
    for row in kf.iterrows():
        temp_key.append(row[1][0])

    temp_poi_id = []
    pkf = pd.read_csv(city_poi_id, encoding='GBK', usecols=['序号', 'poi类别'])
    for row in pkf.iterrows():
        temp_poi_id.append(row[1][1])

    X = []
    y = []
    test_temp_list = []
    for t_i in range(len(temp_list)):
        temp_cut = jieba.analyse.extract_tags(str(temp_list[t_i][0]), topK=20, withWeight=False, allowPOS=allow_pos)
        for l_i in range(0, test_len):
            test_temp_list.append(0)

        for d_i in range(len(temp_cut)):
            if temp_cut[d_i] in temp_key:
                test_temp_poi_index = temp_key.index(str(temp_cut[d_i]))
                if test_temp_poi_index < test_len:
                    test_temp_list[test_temp_poi_index] = 1

        if 1 in test_temp_list:
            flag = 1
        else:
            flag = 0

        if flag == 1:
            if temp_list[t_i][1] != ' ':
                X.append(test_temp_list)
                temp_poi_id_index = temp_poi_id.index(str(temp_list[t_i][1]))
                y.append(temp_poi_id_index)
        test_temp_list = []

    # print(X)
    # print(y)
    # print(len(X))

    modle = MultinomialNB(alpha=1.0, fit_prior=True, class_prior=None)
    modle.fit(X, y)

    test_df = pd.read_csv(test_city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(test_df)
    test_df.dropna(axis=0, how='any', inplace=True)
    test_df_list = test_df.values.tolist()

    test_X = []
    test_X_list = []
    poi_list = []
    poi_index = []
    for t_i in range(len(test_df_list)):
        temp_cut = jieba.analyse.extract_tags(str(test_df_list[t_i][0]), topK=20, withWeight=False, allowPOS=allow_pos)
        for l in range(0, test_len):
            test_X_list.append(0)

        for d_i in range(len(temp_cut)):
            if temp_cut[d_i] in temp_key:
                test_temp_poi_index = temp_key.index(str(temp_cut[d_i]))
                if test_temp_poi_index < test_len:
                    test_X_list[test_temp_poi_index] = 1

        if 1 in test_X_list:
            test_flag = 1
        else:
            test_flag = 0

        if test_flag == 1:
            poi_index.append(t_i)
            poi_list.append(test_df_list[t_i])
            test_X.append(test_X_list)
        test_X_list = []

    # print(test_X)
    # print(len(test_X))
    time = 0
    pred = modle.predict(test_X)
    for i in range(len(pred)):
        if pred[i] < 20:
            if test_df_list[poi_index[i]][1] == temp_poi_id[pred[i]]:
                time += 1
            # print(poi_list[i], temp_poi_id[pred[i]])
    print(str(time/len(pred))+'%')

'''
    测试朴素贝叶斯分类：
'''
def multinomialNB_classify(city_poi, city_poi_id, key, test_city_poi, sy_poi, allow_pos):
    df = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    temp_list = df.values.tolist()

    temp_key = []
    kf = pd.read_csv(key, encoding='GBK', usecols=['关键字'])
    test_len = len(kf)
    for row in kf.iterrows():
        temp_key.append(row[1][0])

    temp_poi_id = []
    pkf = pd.read_csv(city_poi_id, encoding='GBK', usecols=['序号', 'poi类别'])
    for row in pkf.iterrows():
        temp_poi_id.append(row[1][1])

    X = []
    y = []
    test_temp_list = []
    for t_i in range(len(temp_list)):
        temp_cut = jieba.analyse.extract_tags(str(temp_list[t_i][0]), topK=5, withWeight=False, allowPOS=allow_pos)
        for l_i in range(0, test_len):
            test_temp_list.append(0)

        for d_i in range(len(temp_cut)):
            if temp_cut[d_i] in temp_key:
                test_temp_poi_index = temp_key.index(str(temp_cut[d_i]))
                if test_temp_poi_index < test_len:
                    test_temp_list[test_temp_poi_index] = 1

        if 1 in test_temp_list:
            flag = 1
        else:
            flag = 0

        if flag == 1:
            if temp_list[t_i][1] != ' ':
                X.append(test_temp_list)
                if str(temp_list[t_i][1]) in temp_poi_id:
                    temp_poi_id_index = temp_poi_id.index(str(temp_list[t_i][1]))
                    y.append(temp_poi_id_index)
        test_temp_list = []

    modle = MultinomialNB(alpha=1.0, fit_prior=True, class_prior=None)
    modle.fit(X, y)

    df = pd.read_csv(test_city_poi, encoding='GBK', usecols=['聚类点经度', '聚类点纬度', '聚类点', '经度', '纬度', '地点名'])
    pd.DataFrame(df)
    df.dropna(axis=0, how='any', inplace=True)
    tf = pd.read_csv(city_poi, encoding='GBK', usecols=['地点名', 'POI类别'])
    pd.DataFrame(tf)
    tf.dropna(axis=0, how='any', inplace=True)
    poi_inner = pd.merge(df, tf, how='inner', on='地点名')
    poi_inner = pd.DataFrame(poi_inner)
    poi_inner.drop_duplicates('地点名', inplace=True)
    poi_inner.duplicated()
    poi_list = poi_inner.values.tolist()
    with open(sy_poi, 'w', newline='') as datafile:
        write = csv.writer(datafile)
        write.writerow(['聚类点经度', '聚类点纬度', '聚类点', '经度', '纬度', '地点名', 'POI类别'])
    exist_poi_len = len(poi_list)
    exist_poi = []
    for i in range(exist_poi_len):
        with open(sy_poi, 'a+', newline='') as datafile:
            write = csv.writer(datafile)
            write.writerow(poi_list[i])
        exist_poi.append(poi_list[i][5])

    # for p_i in range(len(poi_list)):
    #     exist_poi.append(poi_list[p_i][5])

    test_df = pd.read_csv(test_city_poi, encoding='GBK', usecols=['聚类点经度', '聚类点纬度', '聚类点', '经度', '纬度', '地点名'])
    test_df_list = []
    pd.DataFrame(test_df)
    test_df.dropna(axis=0, how='any', inplace=True)
    test_df = test_df.values.tolist()
    temp_poi_list = []
    for i in range(len(test_df)):
        if test_df[i][5] not in exist_poi:
            test_df_list.append(test_df[i][5])
            temp_poi_list.append(test_df[i])
    test_X = []
    test_X_list = []
    poi_list = []
    for t_i in range(len(test_df_list)):
        temp_cut = jieba.analyse.extract_tags(str(test_df_list[t_i]), topK=5, withWeight=False, allowPOS=allow_pos)
        for l in range(0, test_len):
            test_X_list.append(0)

        for d_i in range(len(temp_cut)):
            if temp_cut[d_i] in temp_key:
                test_temp_poi_index = temp_key.index(str(temp_cut[d_i]))
                if test_temp_poi_index < test_len:
                    test_X_list[test_temp_poi_index] = 1

        if 1 in test_X_list:
            test_flag = 1
        else:
            test_flag = 0

        if test_flag == 1:
            poi_list.append(temp_poi_list[t_i])
            test_X.append(test_X_list)
        test_X_list = []

    pred = modle.predict(test_X)
    for i in range(len(pred)):
        if pred[i] < 20:
            t_poi_list = poi_list[i][:]
            t_poi_list.append(temp_poi_id[pred[i]])
            with open(sy_poi, 'a+', newline='') as datafile:
                write = csv.writer(datafile)
                write.writerow(t_poi_list)
    print(str((len(pred)+exist_poi_len)/len(df))+'%')
