<div class="mx-1 mt-8">
	<div class="w-full bg-green p-4 text-center font-bold text-xl text-white">
		Datenpool
	</div>
	<p class="mt-8 text-lg mb-8"><strong>Im „Datenpool“ finden Sie relevante Datensätze zu Ressourcen-Themen im Originalformat.</strong></p>
	<section id="app" class="view">
		<div is="datenpool"
		     :json='<?php print json_encode($variables['json']); ?>'
		     inline-template>
			<div class="mt-8 px-1">
				<div class="flex">
					<div class="w-1/4 bg-grey-light p-4">
						<h3>Filter:</h3>
						<hr class="border-grey border-b border-t-0 border-l-0 border-r-0">
						<div class="mb-2">Quelle:</div>
						<div v-for="(item, index) in getUniqueList('source',this.xhr.data)">
							<input type="checkbox" :id="'sources-'+index" :value="item" v-model="filter.source">
							<label class="inline-block" :for="'sources-'+index">{{item}}</label>
						</div>
						<hr class="border-grey border-b border-t-0 border-l-0 border-r-0">
						<div class="mb-2">Territorialbezug:</div>
						<div v-for="(item, index) in getUniqueList('reference',this.xhr.data)">
							<input type="checkbox" :id="'references-'+index" :value="item" v-model="filter.reference">
							<label class="inline-block" :for="'references-'+index">{{item}}</label>
						</div>
					</div>
					<div class="w-3/4 ml-4">
						<div class="bg-grey-light p-4"
						     v-for="(item, index) in filteredItems(this.xhr.data, this.filter)">
							<input class="hidden" type="radio" :id="item.title+'-'+index" :value="item" v-model="data.indikator">
							<label class="indikator mb-5 text-base" :for="item.title+'-'+index">
								<a :href="item.url" class="text-black no-underline" >
									<strong>{{item.title}}</strong> {{item.source | indikator}}
								</a>
							</label>
						</div>
						<div v-if="filteredItems(this.xhr.data, this.filter).length === 0" class="bg-grey-light p-4">
							Keine Daten vorhanden.
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
