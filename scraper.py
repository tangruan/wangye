import sys
import json
import io
from google_play_scraper import search, app, Sort, reviews
from datetime import datetime

# 设置标准输出和错误输出的编码为 UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8')

def search_app(keyword):
    search_result = search(
        keyword,  # 搜索关键词
        lang="en",  # 语言设置
        country="us",  # 国家/地区设置
        n_hits=1  # 返回结果的最大数量
    )
    return search_result

def get_app_details(app_package):
    app_result = app(
        app_package,
        lang='zh',  # 语言设置
        country='jp'  # 国家/地区设置
    )
    return app_result

def get_reviews(app_package):
    print(f"Fetching reviews for package: {app_package}")
    reviews_result, continuation_token = reviews(
        app_package,
        lang='zh',  # 默认语言为中文
        country='us',  # 默认国家为美国
        sort=Sort.NEWEST,  # 按最新排序
        count=1,  # 获取 1 条评论
        filter_score_with=5  # 只获取评分为 5 星的评论
    )
    print(f"Reviews result: {reviews_result}")
    if continuation_token:
        reviews_result, _ = reviews(
            app_package,
            continuation_token=continuation_token
        )
    return reviews_result

def serialize_datetime(obj):
    """将 datetime 对象序列化为 ISO 8601 格式字符串"""
    if isinstance(obj, datetime):
        return obj.isoformat()
    raise TypeError(f"Type {obj.__class__.__name__} not serializable")

def main():
    print(f"Arguments: {sys.argv}")
    
    if len(sys.argv) < 2:
        print("No action specified")
        return

    action = sys.argv[1]
    print(f"Action: {action}")
    if action == 'search' and len(sys.argv) > 2:
        keyword = sys.argv[2]
        print(f"Keyword: {keyword}")
        result = search_app(keyword)
    elif action == 'details' and len(sys.argv) > 2:
        app_package = sys.argv[2]
        print(f"App package: {app_package}")
        result = get_app_details(app_package)
    elif action == 'reviews' and len(sys.argv) > 2:
        app_package = sys.argv[2]
        print(f"App package: {app_package}")
        result = get_reviews(app_package)
    else:
        result = {'error': 'Invalid parameters'}

    # 使用 ensure_ascii=False 来确保正确编码 UTF-8
    print(json.dumps(result, default=serialize_datetime, ensure_ascii=False))

if __name__ == '__main__':
    main()
