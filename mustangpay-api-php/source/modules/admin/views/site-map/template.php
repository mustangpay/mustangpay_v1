<?php
/**
 * @var $this \yii\web\View
 * @var $models array
 */
echo '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">' . PHP_EOL;
foreach ($urls as $item):
?>
    <url>
        <loc><?= $item[0];?></loc>
        <priority><?= $item[1];?></priority>
        <lastmod><?= $item['time'];?></lastmod>
        <changefreq>daily</changefreq>
        <mobile:mobile type="pc,mobile"/>
    </url>
<?php endforeach;?>
<?php echo '</urlset>' . PHP_EOL;?>
