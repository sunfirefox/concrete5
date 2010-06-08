<? defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<?
if (isset($_REQUEST['searchInstance'])) {
	$searchInstance = $_REQUEST['searchInstance'];
}
?>
<script type="text/javascript">
	var CCM_STAR_STATES = {
		'unstarred':'star_grey.png',
		'starred':'star_yellow.png'
	};
	var CCM_STAR_ACTION    = 'files/star.php';
</script>
<div id="ccm-list-wrapper"><a name="ccm-<?=$searchInstance?>-list-wrapper-anchor"></a>
<?
	$fileList->displaySummary();
	$txt = Loader::helper('text');
	$keywords = $searchRequest['fKeywords'];
	$soargs = array();
	$soargs['searchInstance'] = $searchInstance;

	/*
	if ($searchType == 'DASHBOARD') {
		$bu = false;
	} else {
		$bu = REL_DIR_FILES_TOOLS_REQUIRED . '/files/search_results';
	}
	*/
	
	$bu = REL_DIR_FILES_TOOLS_REQUIRED . '/files/search_results';
	
	if (count($files) > 0) { ?>	
		<table border="0" cellspacing="0" cellpadding="0" id="ccm-<?=$searchInstance?>-list" class="ccm-results-list">
		<tr>
			<th><input id="ccm-<?=$searchInstance?>-list-cb-all" type="checkbox" /></td>
			<th><select id="ccm-<?=$searchInstance?>-list-multiple-operations" disabled>
				<option value="">**</option>
				<option value="download"><?=t('Download')?></option>
				<option value="sets"><?=t('Sets')?></option>
				<option value="properties"><?=t('Properties')?></option>
				<option value="rescan"><?=t('Rescan')?></option>
				<option value="delete"><?=t('Delete')?></option>
			</select>
			</th>
			<th>Type</th>

			<th class="ccm-file-list-starred">&nbsp;</th>			
			<th class="ccm-file-list-filename <?=$fileList->getSearchResultsClass('fvTitle')?>"><a href="<?=$fileList->getSortByURL('fvTitle', 'asc', $bu, $soargs)?>"><?=t('Title')?></a></th>
			<th class="<?=$fileList->getSearchResultsClass('fDateAdded')?>"><a href="<?=$fileList->getSortByURL('fDateAdded', 'asc', $bu, $soargs)?>"><?=t('Added')?></a></th>
			<th class="<?=$fileList->getSearchResultsClass('fvDateAdded')?>"><a href="<?=$fileList->getSortByURL('fvDateAdded', 'asc', $bu, $soargs)?>"><?=t('Active')?></a></th>
			<th class="<?=$fileList->getSearchResultsClass('fvSize')?>"><a href="<?=$fileList->getSortByURL('fvSize', 'asc', $bu, $soargs)?>"><?=t('Size')?></a></th>
			<? 
			$slist = FileAttributeKey::getColumnHeaderList();
			foreach($slist as $ak) { ?>
				<th class="<?=$fileList->getSearchResultsClass($ak)?>"><a href="<?=$fileList->getSortByURL($ak, 'asc', $bu, $soargs)?>"><?=$ak->getAttributeKeyDisplayHandle()?></a></th>
			<? } ?>			
			<th class="ccm-search-add-column-header"><a href="<?=REL_DIR_FILES_TOOLS_REQUIRED?>/files/customize_search_columns?searchInstance=<?=$searchInstance?>" id="ccm-search-add-column"><img src="<?=ASSETS_URL_IMAGES?>/icons/add.png" width="16" height="16" /></a></th>
		</tr>
	<?
		foreach($files as $f) {
			$pf = new Permissions($f);
			if (!isset($striped) || $striped == 'ccm-list-record-alt') {
				$striped = '';
			} else if ($striped == '') { 
				$striped = 'ccm-list-record-alt';
			}
			$star_icon = ($f->isStarred() == 1) ? 'star_yellow.png' : 'star_grey.png';
			$fv = $f->getApprovedVersion(); 
			$canViewInline = $fv->canView() ? 1 : 0;
			$canEdit = ($fv->canEdit() && $pf->canWrite()) ? 1 : 0;
			?>
			<tr class="ccm-list-record <?=$striped?>" ccm-file-manager-instance="<?=$searchInstance?>" ccm-file-manager-can-admin="<?=($pf->canAdmin())?>" ccm-file-manager-can-delete="<?=$pf->canAdmin()?>" ccm-file-manager-can-view="<?=$canViewInline?>" ccm-file-manager-can-replace="<?=$pf->canWrite()?>" ccm-file-manager-can-edit="<?=$canEdit?>" fID="<?=$f->getFileID()?>" id="fID<?=$f->getFileID()?>">
			<td class="ccm-file-list-cb" style="vertical-align: middle !important"><input type="checkbox" value="<?=$f->getFileID()?>" /></td>
			<td>
				<div class="ccm-file-list-thumbnail">
					<div class="ccm-file-list-thumbnail-image" fID="<?=$f->getFileID()?>"><table border="0" cellspacing="0" cellpadding="0" height="70" width="100%"><tr><td align="center" fID="<?=$f->getFileID()?>" style="padding: 0px"><?=$fv->getThumbnail(1)?></td></tr></table></div>
				</div>
		
			<? if ($fv->hasThumbnail(2)) { ?>
				<div class="ccm-file-list-thumbnail-hover" id="fID<?=$f->getFileID()?>hoverThumbnail"><div><?=$fv->getThumbnail(2)?></div></div>
			<? } ?>

				</td>
			<td><?=$fv->getType()?></td>
			<td class="ccm-file-list-starred"><img src="<?=ASSETS_URL_IMAGES?>/icons/<?=$star_icon?>" height="16" width="16" border="0" class="ccm-star" /></td>			
			<td class="ccm-file-list-filename"><?=$txt->highlightSearch(wordwrap($fv->getTitle(), 15, "\n", true), $keywords)?></td>
			<td><?=date(DATE_APP_DASHBOARD_SEARCH_RESULTS_FILES, strtotime($f->getDateAdded()))?></td>
			<td><?=date(DATE_APP_DASHBOARD_SEARCH_RESULTS_FILES, strtotime($fv->getDateAdded()))?></td>
			<td><?=$fv->getSize()?></td>
			<? 
			$slist = FileAttributeKey::getColumnHeaderList();
			foreach($slist as $ak) { ?>
				<td><?
				$vo = $fv->getAttributeValueObject($ak);
				if (is_object($vo)) {
					print $vo->getValue('display');
				}
				?></td>
			<? } ?>		
			<td>&nbsp;</td>
			
			</tr>
			<?
		}

	?>
	
	</table>
	
	

	<? } else { ?>
		
		<div class="ccm-results-list-none"><?=t('No files found.')?></div>
		
	
	<? } 
	$fileList->displayPaging($bu, false, $soargs); ?>
	
</div>