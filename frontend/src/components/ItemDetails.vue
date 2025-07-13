<template>
    <modal v-model="modal"
        style="display: flex; flex-direction: row; padding: 20px; justify-content: center; align-items: center;">
        <p v-if="item" style="font-size: 1.5rem; text-align: center;">{{ item.title }}</p>
        <h1>Historial de transacciones</h1>
        <p style="text-align: center;" v-if="list && list.length === 0">Este ítem aún no registra transacciones</p>
        <div v-for="item in list" :key="item.id" class="item-transaction mb10" >
            <p >{{ item.quantity }} {{ item.quantity > 0 ? 'agregados' : 'descontados' }} (de {{ item.details.from }} a {{ item.details.to }})
            </p>
        </div>
    </modal>

</template>

<script>
import Modal from './Modal.vue';

export default {
    name: 'ItemDetails',
    components: {
        Modal
    },
    props: {
        item: {
            type: Object,
            default: () => ({ title: '' })
        }
    },
    data() {
        return {
            modal: false,
            list: null
        };
    },
    methods: {
        Open(id_item) {
            this.modal = true;
            this.list = axios.get('transactions/' + id_item)
                .then(res => {
                    // console.log(res.data);
                    const sortedData = res.data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                    this.list = sortedData;
                })
                .catch(err => {
                    // console.log('Error message GET item details: ', err);
                });
        }
    }
};
</script>

<style scoped>
p {
    margin: 0;
    padding: 0;
    width: 100%;
}

h1 {
    margin: 0;
    padding: 0;
    margin-bottom: 13px;
    margin-top: 8px;
    font-size: 1.5em;
}

.item-transaction {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    margin: 0 20px;
}

.card {
    border: 1px solid #eee;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
}

.mb10 {
    margin-bottom: 10px;
}
</style>