<template>
	<div class="wrapper">
		<div class="flex flex-no-wrap">
			<div class="w-1/2 flex-none">
				<div class="text-grey-darker p-1">
					<div class="p-2 bg-grey-lighter mb-4">
						<h6 class="block text-greydarkest text-sm mb-0 text-center">Indikator-Beschreibung (Metadaten)</h6>
					</div>
					<div class="text-normal text-left">
						<p><strong>ID:</strong> {{this.id}}</p>
						<p><strong>Quelle:</strong> {{this.indicator.meta.source}}</p>
						<p><strong>Lizenz:</strong> {{this.indicator.meta.license}}</p>
					</div>
				</div>
			</div>
			<div class="w-1/2 flex-none">
				<div class="text-grey-darker text-center p-1">
					<div class="p-2 bg-grey-lighter mb-4">
						<h6 class="text-greydarkest text-sm mb-0">Indikator-Erläuterung</h6>
					</div>
					<div class="text-normal text-left">
						{{this.indicator.description}}
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
						<td @click="showChildren(item)" class="" :class="{'rot-90 pl-2': item.expanded}">></td>
						<td>{{item.name}}</td>
						<td v-for="value in item.data">{{value}}</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="vue-diagramm" :class="{hidden: !isVisibleDiagram}">
				<highcharts :options="barOptions" ref="highcharts"></highcharts>
				
				<div class="py-2">
					<button type="button" class="bg-grey-darker text-white p-2" @click.prevent="onReset">Zurücksetzen auf vollständige Darstellung</button>
				</div>
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
</template>
<script>
    export default {
        props: ['id', 'url', 'categories', 'series','name','indicator'],
        data() {
            return {
                isVisibleDiagram: false,
	            
                filter: {
                    year: {
                        from:   '',
	                    to:     '',
                    },
	                
                },
                table: {
                    thead: null,
	                tbody: null,
                },
                rows: [],
            }
        },
        mounted() {
            this.updateTable();
            console.log(this.indicator);
        },
	    computed: {
            startYearPlaceholder() {
                return this.categories[0];
            },
            endYearPlaceholder() {
                return this.categories[ this.categories.length - 1 ];
            },
		    startYear() {
                return this.filter.year.from === '' ? this.categories[0] : this.filter.year.from;
		    },
            endYear() {
                return this.filter.year.to === '' ? this.categories[this.categories.length-1] : this.filter.year.to;
		    },
            boolArray() {
                let startPos = this.categories.indexOf(this.startYear);
                let endPos = this.categories.indexOf(this.endYear);

                if(startPos < 0) { startPos = this.categories.indexOf(this.startYearPlaceholder); }
                if(endPos < 0) { endPos = this.categories.indexOf(this.endYearPlaceholder); }
                if(startPos > endPos) {
                    let tmpPos = startPos;
                    startPos = endPos;
                    endPos = tmpPos;

                    let tmpVal = this.filter.year.from;
                    this.filter.year.from = this.filter.year.to;
                    this.filter.year.to = tmpVal;
                }

                let boolArray = [];
                for(let i = 0; i < this.categories.length; i++) {
                    boolArray[i] = true;
                    if(i < startPos) { boolArray[i] = false; }
                    if(i > endPos ) { boolArray[i] = false; }
                }
                return boolArray;
            },

            barOptions() {
                let vm = this;
                let result = {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null,
                    },
                    navigation: {
                        buttonOptions: {
                            enabled: false
                        }
                    },
                    xAxis: {
                        categories: [],
                    },
                    yAxis: {
                        title: {
                            text: null,
                        }
                    },
                    series: []
                };
                result.title.text = vm.name;
                result.xAxis.categories = vm.table.thead;
                result.series = [];

                if(vm.table.tbody !== null) {
                    for(let i = 0; i < vm.table.tbody.length; i++) {
                        if(vm.table.tbody[i].checked) {
                            result.series.push(JSON.parse(JSON.stringify(vm.table.tbody[i])));
                        }
                    }
                }
                
                return result;
            },
	    },
	    methods: {
            capitalize(value) {
                let curr_item_arr = value.split('-');
                let curr_level = curr_item_arr[1].length - 1;
                return curr_level;
            },
            updateTable() {
                if(this.table.tbody === null) { this.table.tbody = JSON.parse(JSON.stringify(this.series)); }
                
                let boolArray = this.boolArray;
                
                let thead = [];
                for(let c = 0; c < this.categories.length; c++) {
                    if(boolArray[c]) { thead.push(this.categories[c]); }
	            }
	            this.table.thead = thead;
                
                
                let tbody = [];
                for(let i = 0; i < this.table.tbody.length; i++) {
                    let obj = JSON.parse(JSON.stringify(this.table.tbody[i]));
                    let tmp_data = [];
                    for(let d = 0; d < obj.data.length; d++) {
                        if(boolArray[d]) { tmp_data.push(obj.data[d]); }
                    }
                    obj.data = tmp_data;
                    tbody.push(obj);
                }
	            this.table.tbody = tbody;
            },

            showChildren(item) {
                if(item.expanded) {
                    item.expanded = false;
                } else {
                    item.expanded = true;
                }
                let curr_item_arr = item.level.split('-');
	            let curr_level = curr_item_arr[1].length - 1;
	            let curr_category = curr_item_arr[0];
                for(let i = 0; i < this.table.tbody.length; i++) {
                    let row_item_arr = this.table.tbody[i].level.split('-');
                    let row_level = row_item_arr[1].length - 1;
                    let row_category = row_item_arr[0];
	                let isHidden = JSON.parse(JSON.stringify(this.table.tbody[i].isHidden));
	                if( isHidden ) {
                        if( row_category === curr_category && row_level === curr_level + 1 && this.table.tbody[i].level.indexOf(item.level) === 0) {
                            this.table.tbody[i].isHidden = !isHidden;
                        }
	                } else {
                        if( row_category === curr_category && row_level >= curr_level + 1) {
                            this.table.tbody[i].isHidden = !isHidden;
                            this.table.tbody[i].expanded = false;
                        }
	                }
                }
            },
		    
            onSubmit() {
                _paq.push(['trackSiteSearch',
                    // Search keyword searched for
                    "Banana",
                    // Search category selected in your search engine. If you do not need this, set to false
                    "Organic Food",
                    // Number of results on the Search results page. Zero indicates a 'No Result Search Keyword'. Set to false if you don't know
                    0
                ]);
                this.updateTable();
            },

            onReset(){
                this.filter.year.from = '';
                this.filter.year.to = '';
                this.table.tbody = null;
                this.updateTable();
            },

            showDiagram(bool){
                this.isVisibleDiagram = bool;
            },

            myExport(type) {
                this.$refs.highcharts.chart.exportChartLocal({ type: type });
            },
            myPrint() { this.$refs.highcharts.chart.print() },
            getCSV() { this.$refs.highcharts.chart.downloadCSV() },
            getXLS() { this.$refs.highcharts.chart.downloadXLS() }
	    }
     
    }
</script>


<style lang="scss">
	.level-1 {
		background-color: darken(#fff,0%);
	}
	.level-2 {
		background-color: darken(#fff,5%);
	}
	.level-3 {
		background-color: darken(#fff,10%);
	}
	.level-4 {
		background-color: darken(#fff,15%);
	}
	.level-5 {
		background-color: darken(#fff,20%);
	}
	.level-6 {
		background-color: darken(#fff,25%);
	}
	.level-7 {
		background-color: darken(#fff,30%);
	}
	.rot-90 {
		transform: rotate(90deg);
	}
</style>