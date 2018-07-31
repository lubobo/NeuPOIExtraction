#-*-coding:utf-8-*-
import pandas as pd
from datetime import datetime
from DataCleaning import dis_taxi
from DataCleaning import dis_temp_data
from CreatePOI import MiniBatchKMeans_data
from CreatePOI import temp_poi
from Classify import get_poi_id
from Classify import get_poi_key
from Classify import get_key
from Classify import get_train_data
from Classify import multinomialNB_classify
from Classify import test_multinomialNB_classify
import math
import sys

'''
    GPS数据格式转换
'''
dis_temp_data(sys.argv[1], sys.argv[2])

'''
    获取上下车点数据
'''
# dis_taxi('sy_temp_data.csv', 'sy_taxi_data.csv')

'''
    KMeans提取聚类点
'''
# MiniBatchKMeans_data('sy_taxi_data.csv', 'sy_means_data.csv')

'''
    生成基本测试POI数据
'''
# temp_poi('sy_means_data.csv', 'sy_temp_poi.csv')

'''
    获取训练集数据
'''
# get_train_data('sy_temp_poi.csv', 'sy_test_poi.csv')

'''
    获取poi_id.csv文件：
'''
# get_poi_id('sy_test_poi.csv', 'sy_poi_id.csv')

'''
    定义jieba分词词性：
'''
# allow_pos = (['an', 'un', 'i', 'nt', 'nl', 'nsf', 'nz', 'nr', 'nrf', 'nrj',
#               'ng', 'ns', 'n', 's', 'vn', 'vd', 'vl', 'v', 'r', 'j', 'l'])

'''
    获取poi_key.csv文件：
'''
# get_poi_key('sy_test_poi.csv', 'sy_poi_key.csv', allow_pos)

'''
    获取key.csv文件：
'''
# df = pd.read_csv('sy_poi_key.csv', encoding='GBK', usecols=['关键字'])
# key_len = len(df)
# get_key('sy_test_poi.csv', 'sy_poi_id.csv', 'sy_poi_test_key.csv', allow_pos)

'''
    训练朴素贝叶斯分类：
'''
# tf = pd.read_csv('sy_poi_test_key.csv', encoding='GBK', usecols=['关键字'])
# test_len = len(tf)
# test_multinomialNB_classify('sy_test_poi.csv', 'sy_poi_id.csv', 'sy_poi_test_key.csv',
#                             'sy_test_poi.csv', test_len, allow_pos)

'''
    测试朴素贝叶斯分类：
'''
# tf = pd.read_csv('sy_poi_test_key.csv', encoding='GBK', usecols=['关键字'])
# test_len = len(tf)
# multinomialNB_classify('sy_test_poi.csv', 'sy_poi_id.csv', 'sy_poi_test_key.csv',
#                        'sy_temp_poi.csv', test_len, allow_pos)























