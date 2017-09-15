<script>
    import Replies from '../components/Replies.vue'
    export default {
        components: {
            Replies
        },
        name: 'ThreadPage',
        props: [
            'initialRepliesCount'
        ],
        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>
