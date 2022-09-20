<template>
    <div>
        <select name="" id="" v-model="company_selected_id" class="form-control">
            <option v-for="company, k in companies" :value="company.id">{{ company.name }}</option>
        </select>
        <input data-repeater-create type="button" value="Add" @click="addRow()" class="btn btn-success"/>
        <div class="row">
            <div class="col-sm-6" v-for="company, k in companies_chosen">
                <h3>
                    {{ company.name }}
                    <i class="fa fa-times icon-delete" @click="removeRow(company)"></i>
                </h3>
                <h5>Active groups</h5>
                <div class="groups">
                    <div class="group-row" v-for="group in company.groups">
                        <label :for="group.id">
                            <input type="checkbox"
                                   v-model="group.checked"
                                   :id="group.id"
                                   :name="inputName(company)"
                                   :value="group.id"
                            />
                            {{ group.name }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                companies: [],
                companies_chosen: [],
                company_selected_id: 0
            };
        },
        props: ['id'],
        methods: {
            groupsParseChecked: function (groups, company) {
                console.log('groupsParseChecked')
                let group_ids = JSON.parse(company.pivot.group_ids);

                if (group_ids.length) {
                    groups = this.setGroupsCheckedFromDb(groups, group_ids);
                } else {
                    groups = this.setGroupsCheckedAll(groups);
                }

                return groups;
            },
            setGroupsCheckedFromDb: function (groups, group_ids) {
                console.log('setGroupsCheckedFromDb')
                for (var groupsKey in groups) {
                    groups[groupsKey].checked = this.isChecked(groups[groupsKey], group_ids);
                }

                return groups;
            },
            setGroupsCheckedAll: function (groups) {
                console.log('setGroupsCheckedAll')
                for (var i = 0; i < groups.length; i++) {
                    groups[i].checked = 1;
                }

                return groups;
            },
            isChecked: function (group, group_ids) {
                let out = false;

                if (group_ids.indexOf(group.id+'') > -1) {
                    out = true;
                }

                return out;
            },
            setCompaniesChosen: function (companies, companiesWithGroups) {
                console.log('setCompaniesChosen')
                let companies_new = [];

                for (let k in companies) {
                    let company = companies[k];

                    for (let i in companiesWithGroups) {
                        let _company = companiesWithGroups[i];
                        if (_company.id == company.id) {
                            company.groups = this.groupsParseChecked(_company.groups, company);
                        }
                    }
                    this.companies_chosen.push(company);

                    this.removeChosenFromSelectAndOrAdd(company.id, false);
                }
            },
            inputName: function (company) {
                return 'group_ids['+company.id+'][]';
            },
            addRow: function (add_selected) {
                this.removeChosenFromSelectAndOrAdd(this.company_selected_id, true);
            },
            removeChosenFromSelectAndOrAdd: function (company_selected_id, push_to_chosen) {
                console.log('removeChosenFromSelectAndOrAdd')
                let companies_new = [];

                for (let i = 0; i < this.companies.length; i++) {
                    let _company = this.companies[i];
                    if (_company.id == company_selected_id) {
                        if (push_to_chosen) {
                            _company.groups = this.setGroupsCheckedAll(_company.groups);
                            this.companies_chosen.push(_company);
                        }
                    } else {
                        companies_new.push(_company);
                    }
                }

                this.companies = companies_new;
            },
            removeRow: function (company) {
                let companies_chosen_new = [];
                for (let i = 0; i < this.companies_chosen.length; i++) {
                    let _company = this.companies_chosen[i];
                    if (_company.id != company.id)
                        companies_chosen_new.push(_company);
                }
                this.companies.push(company);

                this.companies_chosen = companies_chosen_new;
            },
            fetchData: function () {
                let self = this;
                console.log('self.id', self.id)
                //if (self.id)
                    axios.post("/supergroup/vue",
                        {id: self.id}
                    ).then(function(resp){
                        console.log('resp', resp)
                        self.companies = resp.data.companies;

                        console.log('resp.data.supergroup.companies', resp.data.supergroup.companies)
                        self.setCompaniesChosen(resp.data.supergroup.companies, resp.data.companies);

                        let companies_new = [];
                        for (let i = 0; i < this.companies.length; i++) {
                            let _company = this.companies[i];
                            if (_company.id == this.company_selected_id) {
                                _company.groups = this.setGroupsCheckedAll(_company.groups);
                                this.companies_chosen.push(_company);
                            } else {
                                companies_new.push(_company);
                            }
                        }
                        console.log('self.companies_chosen', self.companies_chosen)
                    });
            }
        },
        beforeMount: function () {
            this.fetchData();
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
