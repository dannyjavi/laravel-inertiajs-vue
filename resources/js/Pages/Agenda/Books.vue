<template>
    <div>
        <Calendar :events='events' />
    </div>
</template>

<script>
import Calendar from '../../components/Calendar'

export default {
    name: 'Books',
    props:['events'],
    components: {
        Calendar
    }    
}
</script>

<style>

</style>