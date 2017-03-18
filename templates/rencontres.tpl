<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<div class="pageoptions<"><p><span class="pageoptions warning">{$import_from_ping} </span></p></div>
{if $itemcount > 0}
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>id</th>
		<th>Tour</th>
		<th>Locaux</th>
		<th>Adversaires</th>
		<th>Date</th>
		
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->renc_id}</td>
	<td>{$entry->libelle}</td>
	<td>{$entry->equa}</td>
	<td>{$entry->equb}</td>
	<td>{$entry->date_event}</td>
	<td>{$entry->live}</td>
   
	<!--	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>-->
  </tr>
{/foreach}
 </tbody>
</table>
{else}
 	<p>pas encore de résultats</p>
{/if}
