<template>
    <div>
        <div class="domain-repeater-row clearfix" v-for="dom, d in domains">
            <input :name="inputName(d, 'domains_attrs', 'domain')" placeholder="Domain" :class="{'error-repeater-row': dom.domain_error}" class="form-control" v-model="dom.domain" type="text" @blur="checkDomain(dom, d)" required />
            <small class="form-text text-muted"
                   :class="{ 'error-repeater-message': dom.domain_error }"
                   v-if="dom.domain_error">{{dom.domain_error}}</small>
            <i class="fa fa-times repeater-delete-row" @click="removeRow(dom)"></i>
        </div>

        <input data-repeater-create type="button" value="Add" @click="addRow()" class="btn btn-success"/>
    </div>
</template>

<script type="application/javascript">
    export default {
        data() {
            return {
                company: {},
                domains: [],
            };
        },
        props: ['id'],
        methods: {
            addRow: function () {
                this.domains.push({
                    domain: ''
                });
            },
            removeRow: function (domain) {
                let domains_new = [];

                for (let i = 0; i < this.domains.length; i++) {
                    let _domain = this.domains[i];
                    if (_domain.domain != domain.domain)
                        domains_new.push(_domain);
                }

                this.domains = domains_new;
            },
            fetchData: function () {
                let self = this;
                console.log('self.id', self.id);

                if (self.id)
                    axios.post("/company/vue",
                        {id: self.id}
                    ).then(function(resp){
                        console.log(resp);
                        self.company = resp.data.company;
                        self.domains = resp.data.company.domain_whitelists;
                    });
            },
            inputName: function (k, name, attr) {//console.log('recipient', recipient)
                return name+'['+k+']'+'['+attr+']';
            },
            checkDomain: function(dom, k) {
                const new_domain = dom.domain;

                let position, len = new_domain.length;
                for (let i = 0; i < len; i++) {
                    let x = new_domain.indexOf('.', i);
                    if(x == -1) continue;
                    i = x;
                    position = x;
                }

                let valid = position >= 0 && position < len - 2;

                if (new_domain == "" || valid) {
                    Vue.set(this.domains[k], 'domain_error', null);
                } else {
                    Vue.set(this.domains[k], 'domain_error', 'Enter the correct domain! This domain will not be saved.');
                }
            },
        },
        beforeMount: function () {
            this.fetchData();
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>