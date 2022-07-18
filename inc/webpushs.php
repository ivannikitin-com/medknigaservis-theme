<?php
/**
 * Добавляет скрипт web push
 * По заданию Константита 18.07.2022
 */
add_action( 'wp_head', function() {
    echo PHP_EOL, '<script charset="UTF-8" src="//web.webpushs.com/js/push/f79c0d67b050a4decd888469040ee9da_1.js" async></script>', PHP_EOL;
} );