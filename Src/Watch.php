<?php
    require 'Recommend.php';

    $title = $_GET['title'] ?? 'Unknown Talk';
    $tedUrl = $_GET['url'] ?? '';
    $user_id = $_GET['user_id'] ?? 0;

    $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $tedUrl);
    $similar_talks = get_recommendations_by_content($title, 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="Index_Styles.css">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <div class="return-home">
        <a href="index.php?user_id=<?= $user_id; ?>">Back to Home</a>
    </div>

    <div class="video-player">
        <h1><?= htmlspecialchars($title) ?></h1>

        <?php if ($tedUrl): ?>
            <iframe width="560" height="315"
                src="<?= htmlspecialchars($embedUrl); ?>"
                frameborder="0" allow="autoplay; fullscreen" allowfullscreen>
            </iframe>
        <?php else: ?>
            <p>Video unavailable.</p>
        <?php endif; ?>
    </div>

    <div class="rate">
        <!-- Rating system can go here -->
        <form id="likeForm">
            <button type="button" data-like="1">👍 Like</button>
            <button type="button" data-like="0">👎 Dislike</button>
        </form>
    </div>

    <div class="recommendations">
        <h2>Similar Talks</h2>

        <div class="more-from-event"></div>
        <div class="similar-talks">
            <?php foreach (array_slice($similar_talks, 0, 10) as $talk): ?>
                <div class="talk">
                    <div><a href="watch.php?title=<?= urlencode($talk['title']); ?>&url=<?= urlencode($talk['url']); ?>&user_id=<?= urlencode($user_id) ?>">Watch Talk</a></div> 
                    <?php $embedUrl = str_replace('www.ted.com', 'embed.ted.com', $talk['url']); ?>
                    <iframe src="<?= htmlspecialchars($embedUrl) ?>" autoplay="0" frameborder="0"  sandbox="allow-same-origin"></iframe>                    
                    <div class="title"><?= htmlspecialchars($talk['title']); ?></div>
                    <div class="speaker">by <?= htmlspecialchars($talk['main_speaker']); ?></div>

                    <div class="views"><?= number_format((int)$talk['views']) ?> views</div>
                    <br>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Function to get URL parameter by name
    function getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    const userId = getQueryParam('user_id');
    const title = getQueryParam('title');

    document.querySelectorAll('#likeForm button').forEach(btn => {
      btn.onclick = () => {
        const valor = btn.dataset.like;

        fetch('Register_Interactions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `like=${encodeURIComponent(valor)}&user_id=${encodeURIComponent(userId)}&title=${encodeURIComponent(title)}`
        })
        .then(response => {
          if (response.ok) {
            alert('¡Voto enviado!');
          } else {
            alert('Error al enviar');
          }
        })
        .catch(() => alert('Error de red'));
      };
    });
  });
</script>


