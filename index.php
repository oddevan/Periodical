<?php

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;

require_once(__DIR__ . '/vendor/autoload.php');

$mdenv = new Environment();
$mdenv->addExtension(new CommonMarkCoreExtension());
$mdenv->addExtension(new FrontMatterExtension());

$md = new MarkdownConverter($mdenv);

$issue = Yaml::parseFile(__DIR__ . '/issue.yml');

$issue['storyData'] = [];
$issueHtml = [];
foreach ($issue['stories'] as $filename) {
	$contents = file_get_contents(__DIR__ . '/' . $filename);
	$result = $md->convert($contents);

	$issue['storyData'][] = $result->getFrontMatter();
	$issueHtml[] = $result->getContent();
}

?>
<!DOCTYPE html>
<head>
	<title>Periodical</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fanwood+Text:ital@0;1&family=Raleway:wght@300&display=swap">
	<link rel="stylesheet" href="layout.css">
</head>
<body>
<!--
<?php print_r($issue); ?>
-->

<section class="toc">

<h2>In this issue</h2>

<ol>
	<?php foreach ($issue['storyData'] as $info) : ?>
		<li>
			<h3><?= $info['title'] ?></h3>
			<?php if (!empty($info['synopsis'])) : ?>
				<p><?= $info['synopsis'] ?></p>
			<?php endif; ?>
			<?php if (isset($info['rating'])) : ?>
				<p class="rating"><b>Rated <?= $info['rating'] ?></b>
				<?php if (!empty($info['warnings'])) : ?>
					Contains <?= implode(', ', $info['warnings']) ?>
				<?php endif; ?>
				</p>
			<?php endif ?>
	<?php endforeach; ?>
</ol>

<?php if (!empty($issue['cover'])) : ?>
<p class="cover-credit">Cover<?=
	empty($issue['cover']['title']) ? '' : ': &quot;' . $issue['cover']['title'] . '&quot;'
?> by <?= $issue['cover']['artist'] ?></p>
<?php endif; ?>

<p class="colophon"><?= $issue['colophon'] ?></p>

</section>

<?php foreach ($issueHtml as $index => $story) : ?>
	<?php $info = $issue['storyData'][$index]; ?>
	<section>
	<h2><?= $info['title'] ?></h2>
	<?= $story ?>
	<?php if (!empty($info['stinger'])) : ?>
		<p class="stinger"><?= $info['stinger'] ?></p>
	<?php endif; ?>
	</section>
<?php endforeach; ?>
</body></html>