<template>
    <div :class="divClass(hpm)" v-if="hpm?.text?.value">
        {{ hpm.text.value }}
    </div>

</template>
<script>
export default {
    props: ['hpm', 'homepageStore'],
    components: {},

    methods: {
        divClass(hpm) {
            var my_class = 'd-flex flex-row align-center w-100 h-100';

            if (hpm.color) my_class += " " + hpm.color.value;
            if (hpm.bg_color) my_class += " " + hpm.bg_color.value;
            if (hpm.size) my_class += " " + hpm.size.value;
            if (hpm.font) my_class += " " + hpm.font.value;
            if (hpm.justify) my_class += " " + hpm.justify.value;
            if (hpm.padding) my_class += " " + hpm.padding.value;
            return my_class;
        }

    }
}
</script>