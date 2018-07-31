#-*-coding:utf-8-*-
from Classify import get_key
from Classify import multinomialNB_classify
import sys
'''
    定义jieba分词词性：
'''
allow_pos = (['an', 'un', 'i', 'nt', 'nl', 'nsf', 'nz', 'nr', 'nrf', 'nrj',
              'ng', 'ns', 'n', 's', 'vn', 'vd', 'vl', 'v', 'r', 'j', 'l'])

'''
    获取key.csv文件：
'''
get_key(sys.argv[1], sys.argv[3], allow_pos)

'''
    测试朴素贝叶斯分类：
'''
multinomialNB_classify(sys.argv[1], sys.argv[2], sys.argv[3],
                       sys.argv[4], sys.argv[5], allow_pos)