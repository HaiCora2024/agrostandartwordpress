<?php
/**
 * Plugin Name: Агростандарт — блок-паттерны
 * Description: Готовые Gutenberg-паттерны (шапка, подвал, главная, страницы направлений, карточка продукта, политика конфиденциальности) для сайта agrostandart.by. Собраны из живой версии сайта, ставятся поверх любой темы без конфликтов.
 * Version: 1.0.0
 * Author: Агростандарт
 * Text Domain: agrostandart-patterns
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AS_PATTERNS_DIR', plugin_dir_path( __FILE__ ) );
define( 'AS_PATTERNS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Подключаем шрифты и стили сайта на фронтенде.
 * Всё живёт внутри .as-agrostandart — глобальные правила темы не трогает.
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'agrostandart-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Golos+Text:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'agrostandart-design-system',
		AS_PATTERNS_URL . 'assets/css/agrostandart-design-system.css',
		array( 'agrostandart-fonts' ),
		filemtime( AS_PATTERNS_DIR . 'assets/css/agrostandart-design-system.css' )
	);
	wp_enqueue_script(
		'agrostandart-site',
		AS_PATTERNS_URL . 'assets/js/site.js',
		array(),
		filemtime( AS_PATTERNS_DIR . 'assets/js/site.js' ),
		true
	);
} );

/**
 * Категория паттернов "Агростандарт" в инструменте вставки блоков.
 */
add_action( 'init', function () {
	if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( 'agrostandart' ) ) {
		register_block_pattern_category( 'agrostandart', array( 'label' => __( 'Агростандарт', 'agrostandart-patterns' ) ) );
	}
} );

/**
 * Регистрируем каждый файл из patterns/ как отдельный паттерн.
 * Порядок вставки на странице: 00-header -> тело нужной страницы -> 01-footer.
 * Плейсхолдер {{PLUGIN_URL}} в HTML заменяется на реальный URL плагина —
 * так пути к фото и PDF работают независимо от того, куда установлен плагин
 * (обычный plugins/ или mu-plugins/).
 */
add_action( 'init', function () {
	$titles = array(
		'00-header'                        => 'Агростандарт — Шапка',
		'01-footer'                        => 'Агростандарт — Подвал',
		'02-glavnaya'                      => 'Агростандарт — Главная (тело страницы)',
		'03-produktsiya-krs-doynoe'        => 'Агростандарт — Дойное стадо',
		'04-produktsiya-krs-otkorm'        => 'Агростандарт — Откорм КРС',
		'05-produktsiya-svini'             => 'Агростандарт — Свиньи',
		'06-produktsiya-indeyka'           => 'Агростандарт — Индейка',
		'07-produktsiya-bvmkk-61-1s-k'     => 'Агростандарт — Карточка продукта БВМКК-61-1С-к',
		'08-politika-konfidencialnosti'    => 'Агростандарт — Политика конфиденциальности',
	);

	foreach ( $titles as $slug => $title ) {
		$file = AS_PATTERNS_DIR . 'patterns/' . $slug . '.html';
		if ( ! file_exists( $file ) ) continue;

		$content = file_get_contents( $file );
		$content = str_replace( '{{PLUGIN_URL}}', AS_PATTERNS_URL, $content );

		register_block_pattern(
			'agrostandart/' . $slug,
			array(
				'title'      => $title,
				'categories' => array( 'agrostandart' ),
				'content'    => $content,
			)
		);
	}
} );
