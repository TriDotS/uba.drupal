<div id="app">
	<ressourcen-auswertung2 id="<?php print $variables['indicator_id']; ?>" url="http://uba.webshox.org/api/indicators/" :series='<?php print json_encode($variables['preprocessed_indicator']->series); ?>' :categories='<?php print json_encode($variables['preprocessed_indicator']->categories); ?>' name='<?php print $variables['preprocessed_indicator']->title->name; ?>'></ressourcen-auswertung2>
	<div is="ressourcenAuswertung" id="<?php print $variables['indicator_id']; ?>" url="http://uba.webshox.org/api/indicators/" :series='<?php print json_encode($variables['preprocessed_indicator']->series); ?>' :categories='<?php print json_encode($variables['preprocessed_indicator']->categories); ?>' name='<?php print $variables['preprocessed_indicator']->title->name; ?>' inline-template >
		<div class="wrapper">
			<div class="flex flex-no-wrap">
				<div class="w-1/2 flex-none">
					<div class="text-grey-darker p-1">
						<div class="p-2 bg-grey-lighter mb-4">
							<h6 class="block text-greydarkest text-sm mb-0 text-center">Indikator-Beschreibung (Metadaten)</h6>
						</div>
						<div class="text-normal text-left">
							<?php if ( $variables['indicator'] ): ?>
								<p><strong>ID:</strong> <?php print $variables['indicator']->id; ?></p>
								<p><strong>Quelle:</strong> <?php print $variables['indicator']->source; ?></p>
								<p><strong>Lizenz:</strong> <?php print $variables['indicator']->license; ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="w-1/2 flex-none">
					<div class="text-grey-darker text-center p-1">
						<div class="p-2 bg-grey-lighter mb-4">
							<h6 class="text-greydarkest text-sm mb-0">Indikator-Erläuterung</h6>
						</div>
						<div class="text-normal text-left">
							<?php if ( $variables['indicator'] ): ?>
								<?php print $variables['indicator']->description ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="w-full my-2">
				<ul class="list-reset">
					<li class="mb-0 p-2 bg-grey-light text-grey-darkest inline-block"
					    :class="{'font-bold': !isVisibleDiagram}"
					    @click="showDiagram(false)">Tabelle</li>
					<li class="mb-0 p-2 bg-grey-light text-grey-darkest inline-block"
					    :class="{'font-bold': isVisibleDiagram}"
					    @click="showDiagram(true)">Diagramm</li>
				</ul>
			</div>
			<div class="w-full max-w-full my-2">
				<div class="vue-table max-w-full" :class="{hidden: isVisibleDiagram}">
					<table class="block overflow-x-auto whitespace-no-wrap">
						<thead class="text-grey-darkest">
						<tr class="bg-grey-light">
							<th class="p-2"></th>
							<th class="p-2"></th>
							<th class="p-2">Indikator-Gliederung</th>
							<th v-for="item in this.table.thead">{{item}}</th>
						</tr>
						</thead>
						<tbody class="my-2 border-0">
							<tr v-for="(item, index) in this.table.tbody" :class="{'hidden': item.isHidden, ['level-'+capitalize(item.level)]:item.level }" >
								<td><input type="checkbox" :value="index" v-model="item.checked"></td>
								<td @click="showChildren(item)" :class="{'rot-90 pl-2': item.expanded}">></td>
								<td>{{item.name}}</td>
								<td v-for="value in item.data">{{value}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="vue-diagramm" :class="{hidden: !isVisibleDiagram}">
					<highcharts :options="barOptions" ref="highcharts"></highcharts>
					
					<div class="py-2"><button type="button" class="bg-grey-darker text-white p-2" @click.prevent="onReset">Zurücksetzen auf vollständige Darstellung</button></div>
					<div class="py-2">
						<button type="button" class="bg-grey-darker text-white p-2" @click.prevent="myPrint">Abblidung drucken</button>
						<div class="float-right">
							<span><strong>Download der Abbildung: </strong></span>
							<button type="button" class="bg-grey-darker text-white p-2" @click="myExport('image/png')">PNG</button>
							<button type="button" class="bg-grey-darker text-white p-2" @click="myExport('image/jpeg')">JPEG</button>
							<button type="button" class="bg-grey-darker text-white p-2" @click="myExport('image/svg+xml')">SVG</button>
						</div>
					</div>
				</div>
				<div class="px-2 py-4 bg-grey-light mb-4 text-sm">
					<h6 class="text-grey-darkest mb-4 font-bold text-sm">Darstellung Einschränken:</h6>
					<div class="form-inline w-full">
						<div class="form-group inline-block mr-2">
							<label class="inline-block" for="startYear1">Ab Jahr:</label>
							<input class="inline-block form-control" type="text" id="startYear1" :placeholder="startYearPlaceholder" v-model="filter.year.from">
						</div>
						<div class="form-group inline-block mr-2">
							<label class="inline-block" for="endYear1">Bis Jahr:</label>
							<input class="inline-block form-control"  type="text" id="endYear1" :placeholder="endYearPlaceholder" v-model="filter.year.to">
						</div>
						<div class="form-group inline-block">
							<button type="button" class="bg-grey-darker text-white p-2" @click.prevent="onSubmit">Anwenden</button>
							<button type="button" class="bg-grey-darker text-white p-2" @click.prevent="onReset">Zurücksetzen</button>
							<!--
							<button type="button" class="bg-grey-darker text-white p-2 float-right" @click.prevent="onReset">Zurücksetzen</button>
							-->
						</div>
					</div>
					<h6 class="text-grey-darkest mb-4 font-bold text-sm mt-6">Ausgewählte Werte Download:</h6>
					<div class="form-inline w-full">
						<div class="form-group inline-block">
							<button type="button" class="bg-grey-darker text-white p-2" @click="getCSV">CSV</button>
							<button type="button" class="bg-grey-darker text-white p-2" @click="getXLS">XLS</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
