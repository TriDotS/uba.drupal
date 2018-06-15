<div class="mx-1 mt-8">
	<div class="w-full bg-green p-4 text-center font-bold text-xl text-white">
		Datenexplorer
	</div>
	<p class="mt-8 text-lg mb-8"><strong>Im „Datenexplorer“ finden Sie Ressourcen-Daten anhand von Materialgruppen.</strong></p>
	<section id="app" class="view">
		<div is="datenexplorer"
		     :json='<?php print json_encode($variables['json']); ?>'
		     inline-template>
			<div class="block">
				<div class="flex">
					<div class="w-1/4">
						<h4 class="mb-4">Matrialart:</h4>
						<div v-for="(item, index) in this.xhr.data.series || []">
							<input type="checkbox" :id="index" :value="item.name" v-model="filter.name">
							<label class="inline-block font-normal py-1" :for="index">{{item.name}}</label>
						</div>
					</div>
					<div class="w-1/2 pr-4">
						<h4 class="mb-4">Quelle:</h4>
						
						<select id="example-getting-started"
						        class="border border-grey"
						        v-model="filter.sources"
						        multiple style="display:block;width:100%">
							<option v-for="item in this.xhr.sources || []">
								{{ item }}
							</option>
						</select>
					</div>
					<div class="w-1/4">
						<h4 class="mb-4">Zeitraum:</h4>
						<select v-model="filter.years" multiple
						        class="border border-grey"
						        style="display:block;width:100%">
							<option v-for="item in this.xhr.data.categories || []"
									class="p-2">
								{{ item }}
							</option>
						</select>
					</div>
				</div>
				<div class="row-fluid border border-grey" style="margin-top:10px;" v-if="validSelection">
					<highcharts :options="barOptions" ref="highcharts"></highcharts>
				</div>
			</div>
		</div>
	</section>
</div>
