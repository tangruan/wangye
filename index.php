<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Play 爬虫</title>
    <style>
        body {
            display: flex;
            justify-content: space-between;
            margin: 0;
            padding: 0;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .section {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .section h2 {
            margin-top: 0;
        }
        .section pre {
            white-space: pre-wrap; /* 保持格式 */
        }
        .section img {
            max-width: 200px;
            margin-top: 10px;
        }
        .example-text {
            font-size: 0.9em;
            color: #555;
        }
        .response-box {
            width: 800px; /* 可以调整为适合的宽度 */
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            height: 100vh; /* 使其占据视口高度 */
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Google Play 爬虫</h1>

        <!-- 搜索应用 -->
        <div class="section">
            <h2>搜索应用</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="search">
                <label for="search_keyword">搜索关键词：</label>
                <input type="text" id="search_keyword" name="keyword" required>
                <button type="submit">搜索</button>
            </form>
            <small class="example-text">示例： "best Pikachu game"</small>
        </div>

        <!-- 获取应用详细信息 -->
        <div class="section">
            <h2>获取应用详情</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="details">
                <label for="app_package">应用包名（例如：com.nianticlabs.pokemongo）：</label>
                <input type="text" id="app_package" name="app_package" required>
                <button type="submit">获取详情</button>
            </form>
            <small class="example-text">示例： "com.nianticlabs.pokemongo"</small>
        </div>

        <!-- 获取应用评论 -->
        <div class="section">
            <h2>获取应用评论</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="reviews">
                <label for="review_app_package">应用包名（例如：com.nianticlabs.pokemongo）：</label>
                <input type="text" id="review_app_package" name="app_package" required>
                <button type="submit">获取评论</button>
            </form>
            <small class="example-text">示例： "com.nianticlabs.pokemongo"</small>
        </div>
    </div>

    <!-- 响应框 -->
    <div class="response-box">
        <?php
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $app_package = escapeshellarg($_POST['app_package'] ?? '');
            $keyword = escapeshellarg($_POST['keyword'] ?? '');
            $command = "";

            switch ($action) {
                case 'search':
                    $command = "python c:/xampp/htdocs/web/scraper.py search $keyword";
                    break;
                case 'details':
                    $command = "python c:/xampp/htdocs/web/scraper.py details $app_package";
                    break;
                case 'reviews':
                    $command = "python c:/xampp/htdocs/web/scraper.py reviews $app_package";
                    break;
                default:
                    echo "<p>无效的操作。</p>";
                    return;
            }

            if ($command) {
                // 捕捉输出和错误信息
                $output = shell_exec($command . " 2>&1");
                // 确保 PHP 能正确处理 UTF-8 编码
                echo "<h3>命令输出</h3>";
                echo "<pre>" . htmlspecialchars($output, ENT_QUOTES, 'UTF-8') . "</pre>";
            }
        }
        ?>
    </div>
</body>
</html>
