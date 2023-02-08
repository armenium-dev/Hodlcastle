<template>
    <div>
        <br>
        <label style="vertical-align: middle" for="filecsv" class="btn btn-primary">Bulk Import</label>
        <input name="file" type="file" id="filecsv" ref="file" @change="handleFileUpload()" class="hide" />
        <br>
        <label for="delete_old">Delete current recipients before import?</label>
        <input class="my-5" type="checkbox" name="delete_old" id="delete_old" v-model="delete_old"/>
        <br>
        <div class="repeater-row clearfix cols-7" v-for="(recipient, k) in recipients">

            <input :name="inputName(k, 'recipients_attrs', 'email')" placeholder="E-mail" :class="{'error-repeater-row': recipient.email_error}" class="form-control" v-model="recipient.email" type="email" required @blur="checkMailAfterEnter(recipient, k)"/>
            <small class="form-text text-muted"
                   :class="{ 'error-repeater-message': recipient.email_error }"
                   v-if="recipient.email_error">{{recipient.email_error}}</small>
            <input :name="inputName(k, 'recipients_attrs', 'first_name')" placeholder="First Name" class="form-control" v-model="recipient.first_name" required />
            <input :name="inputName(k, 'recipients_attrs', 'last_name')" placeholder="Last Name" class="form-control" v-model="recipient.last_name" />
            <!--<input :name="inputName(k, 'recipients_attrs', 'position')" placeholder="Position" class="form-control" v-model="recipient.position"/>-->
            <input :name="inputName(k, 'recipients_attrs', 'department')" placeholder="Department" class="form-control" v-model="recipient.department"/>
            <input :name="inputName(k, 'recipients_attrs', 'location')" placeholder="Location" class="form-control" v-model="recipient.location"/>
            <input :name="inputName(k, 'recipients_attrs', 'mobile')" placeholder="Mobile" class="form-control" v-model="recipient.mobile" pattern="\+[0-9]{1,20}" />
            <input :name="inputName(k, 'recipients_attrs', 'comment')" placeholder="Comment" class="form-control" v-model="recipient.comment"/>
            <i class="fa fa-times repeater-delete-row" @click="removeRow(recipient)"></i>
        </div>

        <input data-repeater-create type="button" value="Add" @click="addRow()" class="btn btn-success"/>
    </div>
</template>

<script type="application/javascript">
    export default {
        data() {
            return {
                group: {},
                delete_old: false,
                recipients: [],
                file: '',
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
            };
        },
        props: ['id'],
        methods: {
            addRow: function () {
                this.recipients.push({
                    email: '',
                    first_name: '',
                    last_name: '',
                    // position: '',
                    department: '',
                    mobile: '',
                });
            },
            removeRow: function (recipient) {
                let recipients_new = [];

                for (let i = 0; i < this.recipients.length; i++) {
                    let _recipient = this.recipients[i];
                    if (_recipient.email != recipient.email)
                        recipients_new.push(_recipient);
                }

                this.recipients = recipients_new;
            },
            handleFileUpload: function() {
                this.file = this.$refs.file.files[0];
                this.submitFile();
            },
            submitFile: function () {
                let self = this;
                let formData = new FormData();
                formData.append('file', self.file);
                formData.append('delete_old', self.delete_old);
                formData.append('id', self.id);

                axios.post( '/import',
                    formData,
                    {
                        contentType: false,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(resp){
                    console.log('SUCCESS!!');
                    self.recipients = resp.data;
                    self.recipients.map((item, index) => {
                        return self.checkMailAfterEnter(item, index)
                    });

                    clearInputFile(document.getElementById('filecsv'));
                })
                .catch(function(e){
                    console.log('FAILURE!!');
                    clearInputFile(document.getElementById('filecsv'));
                });
            },
            fetchData: function () {
                let self = this;
                if (self.id)
                    axios.post("/group/vue",
                        {id: self.id}
                    ).then(function(resp){
                        self.group = resp.data.group;
                        self.recipients = resp.data.group.recipients;

                        self.recipients.map((item, index) => {
                            return self.checkMailAfterEnter(item, index)
                        });
                    });
            },
            inputName: function (k, name, attr) {//console.log('recipient', recipient)
                return name+'['+k+']'+'['+attr+']';
            },
            checkMailAfterEnter: function(recipient, k) {
                let self = this;

                const new_mail = recipient.email;
                if (new_mail == "" || this.reg.test(new_mail)) {
                    Vue.set(this.recipients[k], 'email_error', null);

                    const new_domain = new_mail.split('@')[1];
                    axios.post("/company/checkDomain",
                        {id: self.group.company_id, domain: new_domain}
                    ).then(function(resp){
                        if (resp.data) {
                            Vue.set(self.recipients[k], 'email_error', null);
                        } else {
                            Vue.set(self.recipients[k], 'email_error', 'This domain email is not in the white list. This recipient will be deleted.');
                        }
                    });

                } else {
                    Vue.set(this.recipients[k], 'email_error', 'Enter the correct email! This recipient will be deleted.');
                }

            }
        },
        beforeMount: function () {
            this.fetchData();
        },
        mounted: function() {
            console.log('Component mounted.');
        }
    }
</script>
