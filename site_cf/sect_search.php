<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="content_search_box">
	<table>
		<tr>
			<td><?=GetMessage("SEARCH_TITLE")?></td>
			<td>
				<?$APPLICATION->IncludeComponent("bitrix:search.title", "eshop", array(
					"NUM_CATEGORIES" => "1",
					"TOP_COUNT" => "5",
					"CHECK_DATES" => "N",
					"SHOW_OTHERS" => "Y",
					"PAGE" => "/site_cf/catalog/",
					"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS") ,
					"CATEGORY_0" => array(
						0 => "iblock_catalog",
					),
					"CATEGORY_0_iblock_catalog" => array(
						0 => "all",
					),
					"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
					"SHOW_INPUT" => "Y",
					"INPUT_ID" => "title-search-input",
					"CONTAINER_ID" => "search"
				),
				false
			);?>
			</td>
		</tr>
	</table>
</div>