<template>
    <div>
        <div class="repeater-row clearfix" v-for="(element, idx) in list" >
            <b class="quiz-line-position"><i class="fa fa-circle"></i></b>

            <input type="text" class="form-control quiz-answer" :name="inputName(idx, 'quizs', 'answer')" v-model="element.answer" placeholder="Answer" required />
            <input type="hidden" class="form-control" v-if="element.id" :name="inputName(idx, 'quizs', 'id')" v-model="element.id" />
            <input type="checkbox" class="quiz-correct-answer" :name="inputName(idx, 'quizs', 'correct')" v-model="element.correct"> correct answer
            <i class="fa fa-times repeater-delete-row" @click="removeAt(idx)"></i>
        </div>

        <input data-repeater-create type="button" value="Add Answer" @click="addRow()" class="btn btn-success"/>
    </div>
</template>

<script>
    let id_line = 0;

    export default {
        data() {
            return {
                list: [],
                show: true
            };
        },
        props: ['id'],
        methods: {
            removeAt(idx) {
                if (this.list[idx].id)
                    axios.post("/pagequizs/delete_answer",
                        {id: this.list[idx].id}
                    );

                this.list.splice(idx, 1);
                id_line = this.list.length;

                this.updateComponent();
            },
            addRow: function() {
                id_line++;
                this.list.push({ position_id: id_line });
            },
            inputName: function (k, name, attr) {
                return name+'['+k+']'+'['+attr+']';
            },
            fetchData: function () {
                let self = this;
                if (self.id)
                    axios.post("/pagequizs/get_answers",
                        {id: self.id}
                    ).then(function(resp){
                        id_line = resp.data.answers.length;
                        self.list = resp.data.answers;
                    });
            },
            updateComponent(){
                var self = this;
                self.show = false;

                Vue.nextTick(function (){
                    self.show = true;
                });
            }
        },
        beforeMount: function () {
            this.fetchData();
        },
        mounted: function() {
            console.log('Component mounted.');
        }
    };
</script>