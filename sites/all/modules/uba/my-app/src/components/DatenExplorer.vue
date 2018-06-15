<script>
    export default {
        props: ['json'],
        data: function () {
            const self = this;
            return {
                xhr: {
                    data: [],
                    sources: ['api'],
                    errors: [],
                },
                filter: {
                    years: [],
                    name: [],
                    sources: [],
                },

                chartIsVisible: false,

            }
        },
        created() {
            this.xhr.data = this.json;
        },
        computed: {
            validSelection() {
                let f = this.filter;
                if(f.years.length>0 && f.name.length>0 && f.sources.length>0)
                { return true; } else { return false; }
            },
            barOptions() {
                if(!this.xhr.data) { return {}; }
                let items = this.filteredItems(this.xhr.data, this.filter);
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
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    yAxis: {
                        title: {
                            text: null,
                        }
                    },
                    series: []
                };
                result.title.text = 'Energieträger';
                result.xAxis.categories = this.filter.years;
                result.series = JSON.parse(JSON.stringify(items));
                return result;
            },
        },
        methods: {
            getData(url) {
                if (!url) return false;
                const vm = this;
                $.get( url, function( response ) {
                    //console.log(response);
                    vm.xhr.data = response;
                }, 'jsonp');
            },
            filteredItems(data,filters) {
	            let boolYearArray = [];
                let result = [];
                let i = 0;

                let categories = this.xhr.data.categories;
                for(i = 0; i < categories.length; i++) {
                    boolYearArray.push(this.filter.years.indexOf(categories[i]) >= 0 ? true:false);
                }
                let series = this.xhr.data.series;
                
                console.log(categories);
                
                for(i = 0; i < series.length; i++)
                {
	                if(this.filter.name.indexOf(series[i].name) >= 0) {
                        let oldData = JSON.parse(JSON.stringify(series[i]));
                        let newData = [];

                        for(let j = 0; j<boolYearArray.length; j++)
                        {
                            if(boolYearArray[j]) {
                                newData.push(oldData.data[j]);
                            };
                        }
                        oldData.data = newData;
                        result.push(oldData);
                    };
                };
                return result;


            }

        }
    }
</script>
<style scoped>
    .echarts {
        width: 100%;
        height: 400px;
    }
</style>
