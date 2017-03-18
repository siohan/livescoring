<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Statut</th>
		<th>Mise à jour</th>
		<th>Locaux</th>
		<th>Score</th>
		<th>Adversaire</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{if $entry->closed=='0'}En cours{else}Terminé{/if}</td>
	<td>{$entry->maj|date_format:'%H:%M:%S'}</td>
	<td>{$entry->locaux}</td>
	<td>{$entry->score_locaux} - {$entry->score_adversaires}</td>
	<td>{$entry->adversaires}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}