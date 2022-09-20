<template>
    <div>
        <div class="col-4">
            <div class="btn btn-success button" @click="addVideo">Add Video Page</div>
            <div class="btn btn-success button" @click="addQuiz">Add Quiz Page</div>
            <div class="btn btn-success button" @click="addText">Add Text Page</div>
        </div>

        <div class="col-8">
            <draggable tag="ul" :list="list" class="list-group" handle=".handle">
                <li class="list-group-item" v-for="(element, idx) in list">

                    <i class="fa fa-align-justify handle"></i>

                    <div style="width: 150px; display: inline-block;">
                        <span class="text">{{ element.position_id }}. <span class="badge">{{ element.entity_type }}</span> </span>
                    </div>

                    <input type="text" class="form-control" :name="inputName(idx, 'pages', 'name')" v-model="element.name" placeholder="Page name" required />
                    <input type="hidden" class="form-control" v-if="element.id" :name="inputName(idx, 'pages', 'id')" v-model="element.id" />
                    <input type="hidden" class="form-control" :name="inputName(idx, 'pages', 'entity_type')" v-model="element.entity_type" />

                    <i class="fa fa-times close" @click="removeAt(idx)"></i>
                    <span class="color-edit" v-if="element.id">
                        <a :href="'/page' + element.entity_type + 's/' + element.id + '/edit'"><i class="fa fa-pencil edit"></i></a>
                    </span>

                </li>
            </draggable>
        </div>
    </div>
</template>

<script>
    let id_line = 0;
    import draggable from "vuedraggable";

    export default {
        components: {
            draggable
        },
        data() {
            return {
                list: [],
                dragging: false
            };
        },
        computed: {
            draggingInfo() {
                return this.dragging ? "under drag" : "";
            }
        },
        props: ['id'],
        methods: {
            removeAt(idx) {
                this.list.splice(idx, 1);
                id_line--;
            },
            addVideo: function() {
                id_line++;
                this.list.push({ position_id: id_line, entity_type:"video" });
            },
            addQuiz: function() {
                id_line++;
                this.list.push({ position_id: id_line, entity_type:"quiz" });
            },
            addText: function() {
                id_line++;
                this.list.push({ position_id: id_line, entity_type:"text" });
            },
            inputName: function (k, name, attr) {
                return name+'['+k+']'+'['+attr+']';
            },
            fetchData: function () {
                let self = this;

                if (self.id)
                    axios.post("/courses/vue",
                        {id: self.id}
                    ).then(function(resp){
                        id_line = resp.data.pages.length;
                        self.list = resp.data.pages;
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
<style scoped>
    .button {
        margin-bottom: 10px;
    }
    .handle {
        float: left;
        padding-top: 11px;
        padding-bottom: 8px;
        cursor: move;
        color: #9c9c9c;
    }
    .close {
        float: right;
        padding-top: 8px;
        padding-bottom: 8px;
    }
    .edit {
        float: right;
        padding-top: 8px;
        padding-bottom: 8px;
        padding-right: 8px;
    }
    .color-edit {
        color: #9c9c9c;
    }
    input {
        display: inline-block;
        width: 50%;
    }
    .text {
        margin: 20px;
    }

    .color-edit {
        cursor: pointer;
        color: #696969;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }

    .color-edit:hover, .color-edit:focus {
        color: #696969;
        text-decoration: none;
        opacity: .75;
    }
</style>