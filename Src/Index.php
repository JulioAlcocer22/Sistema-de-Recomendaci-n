<?php 
    require "Utils.php";
    require "Recommend.php";

    $user_id = $_GET["user_id"] ?? 0;

    $popular_talks = get_most_popular_talks(20);
    $user_recommended_talks = get_recommendations_by_user($user_id, 20) ?? get_recommendations_by_user(0, 20);

    $recommendations = [];

    foreach (array_slice($user_recommended_talks, 0, 10) as $talk) {
        $title = htmlspecialchars($talk['title']);
        $similar_talks = get_recommendations_by_content($title, 5);

        if (!empty($similar_talks)) {
            $recommendations[$title] = $similar_talks;
        }
    }

    $random_talk = get_random_talk();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Index_Styles.css">
    <title>Ted Talks</title>
</head>
<body>
    <h1>Ted Talk Recommender</h1>

    <div class="recommendations">
        <h2>Popular Talks</h2>
        <div class="popular-talks">
            <?php foreach (array_slice($popular_talks, 0, 10) as $talk): ?>
                <div class="talk">
                    <?php
                    $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $talk['url']);
                    $embedUrl = rtrim($embedUrl);
                    ?>
                    <div><iframe src="<?= $embedUrl; ?>" autoplay="0" frameborder="0"  sandbox="allow-same-origin"></iframe> </div>
                    <div class="title"><?= htmlspecialchars($talk['title']); ?></div>
                    <div class="speaker">by <?= htmlspecialchars($talk['main_speaker']); ?></div>

                    <div class="views"><?= number_format((int)$talk['views']) ?> views</div>
                    <br>
                 
                    <div class="video"><a href="watch.php?title=<?= urlencode($talk['title']); ?>&url=<?= urlencode($talk['url']); ?>&user_id=<?= urlencode($user_id) ?>">Watch Talk</a></div> 
                </div>
            <?php endforeach; ?>
        </div>

        <div class="you-may-like">

        </div>

        <h2>Similar users watched</h2>
        <div class="similar-users-watched">
            <?php foreach (array_slice($user_recommended_talks, 0, 10) as $talk): ?>
                <div class="talk">
                    <?php $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $talk['url']); ?>
                    <div><iframe src="<?= $embedUrl; ?>" autoplay="0" frameborder="0"  sandbox="allow-same-origin"></iframe> </div>
                    <div class="title"><?= htmlspecialchars($talk['title']); ?></div>
                    <div class="speaker">by <?= htmlspecialchars($talk['main_speaker']); ?></div>

                    <div class="views"><?= number_format((int)$talk['views']) ?> views</div>
                    <br>
                 
                    <div class="video"><a href="watch.php?title=<?= urlencode($talk['title']); ?>&url=<?= urlencode($talk['url']); ?>&user_id=<?= urlencode($user_id); ?>">Watch Talk</a></div> 
                </div>
            <?php endforeach; ?>
        </div>

        <div class="because-you-watched">
            <?php foreach ($recommendations as $talkTitle => $similarTalks): ?>
                <?php if ($talkTitle !== null && $talkTitle !== ''): ?>
                    <div class="recommendation-row">
                        <h2>Because other users watched: <?= htmlspecialchars($talkTitle) ?></h2>
                        <div class="similar-talks-row">
                            <?php foreach ($similarTalks as $talk): ?>
                                <div class="talk">
                                    <?php
                                    $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $talk['url']);
                                    ?>
                                    <div><iframe src="<?= $embedUrl; ?>" autoplay="0" frameborder="0" sandbox="allow-same-origin"></iframe></div>
                                    <div class="title"><?= htmlspecialchars($talk['title']); ?></div>
                                    <div class="speaker">by <?= htmlspecialchars($talk['main_speaker']); ?></div>
                                    <div class="views"><?= number_format((int)$talk['views']) ?> views</div>
                                    <br>
                                    <div class="video">
                                        <a href="watch.php?title=<?= urlencode($talk['title']); ?>&url=<?= urlencode($talk['url']); ?>&user_id=<?= urlencode($user_id); ?>">Watch Talk</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>



        <div class="random-recommendation">
            <h2>Want some random recommendation?</h2>
            <div class="talk">
                <?php
                $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $random_talk['url']);
                ?>
                <div><iframe src="<?= $embedUrl; ?>" autoplay="0" frameborder="0" sandbox="allow-same-origin"></iframe></div>
                <div class="title"><?= htmlspecialchars($random_talk['title']); ?></div>
                <div class="speaker">by <?= htmlspecialchars($random_talk['main_speaker']); ?></div>
                <div class="views"><?= number_format((int)$random_talk['views']) ?> views</div>
                <br>
                <div class="video">
                    <a href="watch.php?title=<?= urlencode($random_talk['title']); ?>&url=<?= urlencode($random_talk['url']); ?>&user_id=<?= urlencode($user_id); ?>" class="random-button">Watch Talk</a>
                </div>
            </div>
        </div>
    <div>
</body>
</html>