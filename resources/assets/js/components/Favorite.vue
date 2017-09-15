<script>
    export default {
        props: [
            'reply'
        ],
        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited,
                loading: false
            }
        },
        methods: {
            favorite() {
                if (this.loading) return;

                let vm = this;
                vm.loading = true;
                axios.post(this.endpoint)
                    .then(res => {
                        vm.active = true;
                        vm.count++;
                        vm.loading = false
                    })
                    .catch(error => {
                        console.log(error);
                        vm.loading = false
                    })
            },
            unfavorite() {
                if (this.loading) return;

                let vm = this;
                vm.loading = true;
                axios.delete(this.endpoint)
                    .then(res => {
                        vm.active = false;
                        vm.count--;
                        vm.loading = false
                    })
                    .catch(error => {
                        console.log(error);
                        vm.loading = false
                    })
            },
            toggle() {
                this.active ? this.unfavorite() : this.favorite()
            }
        },
        computed: {
            classes() {
                return [this.active ? 'btn-primary' : 'btn-default']
            },
            endpoint() {
                return `/replies/${this.reply.id}/favorites`
            }
        }
    }
</script>

<template>
    <button type="submit" class="btn" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>