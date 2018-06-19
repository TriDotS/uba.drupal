<script>
    export default {
        props: ['id', 'url', 'categories', 'series','name'],
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
	            // split level to array "ABC-01234" => ['ABC','01234']
                let curr_item_arr = item.level.split('-');
                // level = stringleght - 1
	            let curr_level = curr_item_arr[1].length - 1;
	            // current category
	            let curr_category = curr_item_arr[0];
	            // loop through all rows
                for(let i = 0; i < this.table.tbody.length; i++) {
                    // split the current row level to array
                    let row_item_arr = this.table.tbody[i].level.split('-');
                    // get the curretn row level
                    let row_level = row_item_arr[1].length - 1;
                    //get the current row category
                    let row_category = row_item_arr[0];
	                let isHidden = this.table.tbody[i].isHidden;
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


<style lang="scss" scoped>
	
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