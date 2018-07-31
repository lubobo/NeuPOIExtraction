#-*-coding:utf-8-*-
from CreatePOI import MiniBatchKMeans_data
import sys
'''
    KMeans提取聚类点
'''
MiniBatchKMeans_data(sys.argv[1], sys.argv[2])