<script>
    export default {
        props: ['url'],
        data() {
            let vm = this;
            return {
                isVisibleDiagram: false,

                xhr: {
                    data: false,
                    errors: null,
                },

                filter: {
                    boolArray: [],
                    boolArrayRows: false
                },
                form: {
                    startYear: null,
                    endYear: null,
                }
            }
        },
        created() {
            this.getData('http://uba.webshox.org/api/indicators/'+this.url);
        },
        computed: {
            filteredCategories: function () {
                return this.filterData('categories','categories', this.filter.boolArray);
            },
            filteredRows: function () {
                console.log(this.xhr.data.series);
                return this.xhr.data.series;
            },

            barOptions() {
                if(!this.xhr.data) { return {}; }
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
                result.title.text = this.xhr.data.title.text;
                result.xAxis.categories = this.filteredCategories;
                result.series = [];
                this.filter.boolArrayRows.filter(function (value,index) {
                    
                    if(value) {
                        result.series.push(JSON.parse(JSON.stringify(vm.xhr.data.series[index])));
                    };
                });
                result.series.filter(function (value,index) {
                    value.data = vm.filteredData(JSON.parse(JSON.stringify(value.data)));
                })
                return result;
            },
        },
        methods: {
            getData(url) {
                if (!url) return false;
                const that = this;
                $.ajax({
                    url: url,
                    success: function (data, status) {
                        that.xhr.data = data;
                        that.postProcessData(that.xhr.data.series);
                        that.setStartValues(that.xhr.data);
                    },
                    error: function (xOptions, textStatus) {
                        console.log(xOptions);
                    }
                });
            },
            setStartValues(data) {
                let categories = this.xhr.data.categories;
                let startYear = categories[0];
                let endYear = categories[categories.length-1];
                if(this.form.startYear === null || this.form.startYear === '') { this.form.startYear = startYear; };
                if(this.form.endYear === null || this.form.endYear === '') { this.form.endYear = endYear; };
                let startPos = categories.indexOf(this.form.startYear < startYear ? startYear : this.form.startYear);
                let endPos = categories.indexOf(this.form.endYear > endYear ? endYear : this.form.endYear);
                let boolArray = [];
                for(let i = 0; i < categories.length; i++) {
                    boolArray[i] = true;
                    if(i < startPos) { boolArray[i] = false; }
                    if(i > endPos ) { boolArray[i] = false; }
                }
                this.filter.boolArray = boolArray;

                if(this.filter.boolArrayRows) { return false; };
                let boolArrayRows = [];
                for(let i = 0; i < this.xhr.data.series.length; i++) {
                    boolArrayRows[i] = true;
                }
                this.filter.boolArrayRows = boolArrayRows;
            },
            filterData(filterKey,arrayKey,boolArray) {
                let self = this;
                if(!self.xhr.data) { return [];};
                if(!self.xhr.data[arrayKey] && self.xhr.data[arrayKey].length) { return [];};
                return self.xhr.data[arrayKey].filter(function (value, index) {
                    if(boolArray[index]) { return value; };
                });
            },
            filteredData(sourceArray) {
                let boolArray = this.filter.boolArray;
                return sourceArray.filter(function (value, index) {
                    if(boolArray[index]) { return value; };
                });
            },

            showChildren(parentLevel) {
                console.log(parentLevel);
                let vm = this;
                let series = this.xhr.data.series;
                $.each(series, function(key,item){
                    
                    if(item.level.startsWith(parentLevel)) {
                        console.log(vm.getLevel(parentLevel)+1);
                        if(item.isHidden && vm.getLevel(parentLevel)+1 == vm.getLevel(item.level)) {
                            item.isHidden = !item.isHidden;
                        } else if(!item.isHidden && vm.getLevel(parentLevel)+1 <= vm.getLevel(item.level)) {
                            item.isHidden = !item.isHidden;
                        }
                    };
                });
            },
            getLevel(level) {
                return level.length;
                //return level.split('-').length;
            },
            getLevelPrefix(level,prefix = '-') {
                let myLevel = this.getLevel(level);
                let result = '';
                for (let i = myLevel; i > 1; i--) {
                    result += prefix;
                }
                return result;
            },

            showDiagram(bool){
                this.isVisibleDiagram = bool;
            },
            onSubmit() {
                this.setStartValues(this.xhr.data);
            },
            onReset(){
                let categories = this.xhr.data.categories;
                let startYear = categories[0];
                let endYear = categories[categories.length-1];
                this.form.startYear = startYear;
                this.form.endYear = endYear;
                let boolArray = [];
                for(let i = 0; i < categories.length; i++) {
                    boolArray[i] = true;
                }
                this.filter.boolArray = boolArray;

                let boolArrayRows = [];
                for(let i = 0; i < this.xhr.data.series.length; i++) {
                    boolArrayRows[i] = true;
                }
                this.filter.boolArrayRows = boolArrayRows;
            },
            myExport(type) {
                this.$refs.highcharts.chart.exportChartLocal({ type: type });
            },
            myPrint() { this.$refs.highcharts.chart.print() },
            getCSV() { this.$refs.highcharts.chart.downloadCSV() },
            getXLS() { this.$refs.highcharts.chart.downloadXLS() },
            postProcessData(data) {
                let vm = this;
                $.each(data, function(key,item) {
                    if( vm.getLevel(item.level) === 2 ) {
                        item.isHidden = false;
                        console.log(item.level);
                    } else {
                        item.isHidden = true;
                    }
                    if( vm.getLevel(item.level) === 3 ) {
                        console.log(item.level  );
                    }
                });
            }
        }
    }
</script>

<style lang="scss">
    .font-bold { font-weight: bold; }

    .text-greydarkest { color: rgb(16,16,16); }

    .bg-greylight { background-color: lightgrey; }

    .p-2 { padding: 0.5rem; }
    .py-2 { padding: 0.5rem 0; }

    .mb-0 { margin-bottom: 0; }
    .mb-4 { margin-bottom: 1rem; }

    .list-reset { list-style: none; padding: 0; margin: 0; }
    .list-reset li { display: inline-block; }

    .vue-table {
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        th {
            padding: 0.5rem;
        }
    }
    .form-inline .form-group {
        display: inline-block;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .form-inline .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
    }
</style>