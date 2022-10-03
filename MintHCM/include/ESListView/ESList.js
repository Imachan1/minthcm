class ESList {
    constructor() {
        this.component = document.querySelector('es-list').vueComponent;
    }

    init() {
        this.setEvents()
        this.getResults()
    }

    setEvents() {
        this.component.$root.$on('getResults', this.getResults.bind(this));
        this.component.$root.$on('savePreferences', this.savePreferences.bind(this))
    }

    getResults() {
        if (this.component.$store.state.isLoading) {
            return
        }
        this.component.$store.commit('setIsLoading', true)
        viewTools.api.callController({
            module: this.component.$store.state.module,
            action: 'ESList',
            dataGET: this.getParams(),
            async: true,
            dataPOST: {
                function_name: 'getResults',
                filters: this.component.$store.state.options.filters
            },
            callback: (data) => {
                this.component.$store.commit('setIsLoading', false)
                try {
                    data = JSON.parse(data);
                } catch {
                    console.error('cannot parse data', data);
                    return;
                }
                this.component.$store.commit('setData', {
                    records: data.results || [],
                    total: data.total || 0,
                })
                if (data.offset) {
                    this.component.$store.commit('setOffset', data.offset)
                }
            }
        });
    }

    getParams() {
        const options = this.component.$store.state.options
        const params = {
            page: options.page,
            itemsPerPage: options.itemsPerPage,
            myObjects: !!options.myObjects,
            searchPhrase: options.searchPhrase ?? '',
        }
        this.component.$store.commit('setTableOptions', {
            page: options.page,
            itemsPerPage: options.itemsPerPage,
        })
        if (params.page !== 1 && options.pageOffsetMap[options.page - 1]) {
            params.offset = options.pageOffsetMap[options.page - 1]
        } else {
            this.component.$store.commit('resetOffset')
        }
        if (options.sortBy) {
            params.sortBy = options.sortBy;
            params.sortOrder = options.sortOrder;
        }
        return params;
    }

    savePreferences() {
        viewTools.api.callController({
            module: this.component.$store.state.module,
            action: 'ESList',
            async: true,
            dataPOST: {
                function_name: 'savePreferences',
                preferences: this.component.$store.state.preferences
            }
        })
    }
}

window.ESList = ESList;
