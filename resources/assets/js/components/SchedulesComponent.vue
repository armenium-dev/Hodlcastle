<template>
    <div>
        <div class="schedule-block clearfix" v-for="schedule in schedules">
            <div class="form-group col-sm-6">
                <input :name="inputName(schedule, 'id')" class="form-control" v-model="schedule.id" type="hidden" />
                <label>Email Template:</label>
                <select :name="inputName(schedule, 'email_template_id')" v-model="schedule.email_template_id" class="form-control">
                    <option v-for="emailTemplate, id in emailTemplates" :value="id">{{ emailTemplate }}</option>
                </select>
            </div>

            <div class="form-group col-sm-4">
                <label>Send to landing:</label><br>
                <input type="checkbox" value="1"
                       class="flat-green"
                       v-model="schedule.send_to_landing"
                       :name="inputName(schedule, 'send_to_landing')"
                />
            </div>

            <div class="form-group col-sm-6">
                <label>Landing:</label>
                <select :name="inputName(schedule, 'landing_id')" v-model="schedule.landing_id" class="form-control">
                    <option v-for="landing, id in landings" :value="id">{{ landing }}</option>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label>Redirect url:</label>
                <input :name="inputName(schedule, 'redirect_url')" v-model="schedule.redirect_url" class="form-control" />
            </div>

            <div class="form-group col-sm-6">
                <label>Domain:</label>
                <select :name="inputName(schedule, 'domain_id')" v-model="schedule.domain_id" class="form-control">
                    <option v-for="domain, id in domains" :value="id">{{ domain }}</option>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label>Schedule:</label>
                <input :name="inputName(schedule, 'schedule_range')" class="form-control datepicker daterange"
                :data-start="schedule.schedule_start" :data-end="schedule.schedule_end" />
            </div>

            <div class="form-group col-sm-6">
                <label>Time range:</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input :name="inputName(schedule, 'time_start')" class="form-control time_start" v-model="schedule.time_start" />
                    </div>
                    <div class="col-xs-6">
                        <input :name="inputName(schedule, 'time_end')" class="form-control time_end" v-model="schedule.time_end" />
                    </div>
                </div>
            </div>

            <div class="form-group col-sm-4">
                <label>Send on Weekend:</label><br>
                <input type="checkbox" value="1"
                       class="flat-green"
                       v-model="schedule.send_weekend"
                       :name="inputName(schedule, 'send_weekend')"
                />
            </div>
            <div class="form-group col-sm-2">
                <label></label><br>
                <button class="btn btn-danger pull-right" type="button" @click="removeRow(schedule)">Remove</button>
            </div>
            <hr style="display: block">
        </div>
        <input data-repeater-create type="button" value="Add" @click="addRow()" class="btn btn-success"/>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                emailTemplates: [],
                landings: [],
                domains: [],
                schedules: [],
                email_template_id: 0,
                landing_id: 0,
                domain_id: 0
            };
        },
        props: ['id'],
        methods: {
            addRow: function () {
                let id = 1;
                for (let i = 0; i < this.schedules.length; i++) {
                    let schedule = this.schedules[i];
                    if (schedule.id >= id) {
                        id = schedule.id+1;
                    }
                }
                this.schedules.push({
                    id: id,
                    email_template_id: 0,
                    landing_id: 0,
                    domain_id: 0,
                    send_weekend: 0
                });
            },
            removeRow: function (schedule) {
                let schedules_new = [];
                for (let i = 0; i < this.schedules.length; i++) {
                    let _schedule = this.schedules[i];
                    if (_schedule.id != schedule.id)
                        schedules_new.push(_schedule);
                }

                this.schedules = schedules_new;
            },
            inputName: function (schedule, attr) {
                return 'schedules['+schedule.id+']['+attr+']';
            },
            fetchData: function () {
                let self = this;
                console.log('self.id', self.id)
                //if (self.id)
                axios.post("/supergroup/vue_schedules",
                    {id: self.id}
                ).then(function(resp){
                    console.log('resp', resp)
                    self.emailTemplates = resp.data.emailTemplates;
                    self.landings = resp.data.landings;
                    self.domains = resp.data.domains;
                    self.schedules = resp.data.supergroup.schedules;

                    setTimeout(function () {
                        initDatepicker();
                        initTimepicker();
                    }, 1000);
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
