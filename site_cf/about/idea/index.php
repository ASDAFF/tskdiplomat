<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (IsModuleInstalled("idea")):
	$APPLICATION->SetTitle("Есть идея?");
?>
	<?$APPLICATION->IncludeComponent("bitrix:idea", ".default", array(
		"MESSAGE_COUNT" => "10",
		"COMMENTS_COUNT" => "10",
		"DATE_TIME_FORMAT" => "d.m.y G:i",
		"NAV_TEMPLATE" => "",
		"SMILES_COUNT" => "2",
		"IMAGE_MAX_WIDTH" => "770",
		"IMAGE_MAX_HEIGHT" => "770",
		"EDITOR_RESIZABLE" => "Y",
		"EDITOR_DEFAULT_HEIGHT" => "300",
		"EDITOR_CODE_DEFAULT" => "N",
		"COMMENT_EDITOR_RESIZABLE" => "Y",
		"COMMENT_EDITOR_DEFAULT_HEIGHT" => "200",
		"COMMENT_EDITOR_CODE_DEFAULT" => "N",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/site_cf/about/idea/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_TIME_LONG" => "604800",
		"PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
		"SET_TITLE" => "Y",
		"SET_NAV_CHAIN" => "Y",
		"BLOG_PROPERTY" => array(
		),
		"BLOG_PROPERTY_LIST" => array(
		),
		"POST_PROPERTY" => array(
		),
		"POST_PROPERTY_LIST" => array(
		),
		"USE_ASC_PAGING" => "N",
		"SHOW_RATING" => "Y",
	        "RATING_TEMPLATE" => "like",
		"COMMENT_ALLOW_VIDEO" => "N",
		"SHOW_SPAM" => "N",
		"NO_URL_IN_COMMENTS" => "",
		"ALLOW_POST_CODE" => "Y",
		"USE_GOOGLE_CODE" => "Y",
		"BLOG_URL" => "idea_cf",
		"NAME_TEMPLATE" => "",
		"SHOW_LOGIN" => "Y",
		"USE_SHARE" => "N",
		"IBLOCK_CATOGORIES" => "10",
		"POST_BIND_USER" => array(
			0 => "1",
		),
		"POST_BIND_STATUS_DEFAULT" => "1",
		"SEF_URL_TEMPLATES" => array(
			"index" => "",
			"status_0" => "status/#status_code#/",
			"category_1" => "category/#category_1#/",
			"category_1_status" => "category/#category_1#/status/#status_code#/",
			"category_2" => "category/#category_1#/#category_2#/",
			"category_2_status" => "category/#category_1#/#category_2#/status/#status_code#/",
			"user_ideas" => "user/#user_id#/idea/",
			"user_ideas_status" => "user/#user_id#/idea/status/#status_code#/",
			"user" => "user/#user_id#/",
			"user_subscribe" => "user/#user_id#/subscribe/",
			"post_edit" => "edit/#post_id#/",
			"post" => "#post_id#/",
			"post_rss" => "#blog#/rss/#type#/#post_id#/",
			"rss" => "#blog#/rss/#type#",
			"rss_status" => "rss/#type#/status/#status_code#/",
			"rss_category" => "rss/#type#/category/#category#/",
			"rss_category_status" => "rss/#type#/category/#category#/status/#status_code#/",
			"rss_user_ideas" => "rss/#type#/user/#user_id#/idea/",
			"rss_user_ideas_status" => "rss/#type#/user/#user_id#/idea/status/#status_code#/",
		)
		),
		false
	);?>
<?
endif;
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>