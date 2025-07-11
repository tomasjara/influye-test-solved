<template>

    <modal v-model="modal">
        <p v-if="item">{{ item.title }}</p>
        <h1>Historial de transacciones</h1>
        <p v-if="list && list.length === 0">Este ítem aún no registra transacciones</p>
        <div v-for="item in list" :key="item.id">
            <div class="card mb10">
                <p style="margin-bottom: 10px;">{{ item.quantity }} {{ item.quantity > 0 ? 'agregados' : 'descontados' }}
                </p>
                <p>de {{ item.details.from }} a {{ item.details.to }}</p>
            </div>
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
                    console.log(res.data);
                    this.list = res.data;
                })
                .catch(err => {
                    console.log('Error message GET item details: ', err);
                });
        }
    }
};
</script>

<style scoped>
p {
    margin: 0;
    padding: 0;
}

h1 {
    margin: 0;
    padding: 0;
    margin-bottom: 13px;
    margin-top: 8px;
    font-size: 1.5em;
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