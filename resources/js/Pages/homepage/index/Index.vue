<template>
    <v-container fluid class="h-100 w-100 d-flex flex-column align-center justify-center">
        <div :class="fontTitle">Das ist der Titel {{ fontTitle }}</div>
        <div :class="fontSubtitle">Das ist der Unteritel</div>
        <div :class="fontContent">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
            aspernatur
            numquam culpa esse voluptas. Laudantium reprehenderit minima dolore eum? Hic nam impedit ratione quod
            consequuntur? Illum quae sed perferendis distinctio error vero doloribus nostrum ipsam, praesentium facere
            ex
            totam alias adipisci at quod nulla in obcaecati voluptatum aliquid quo optio fugit. Obcaecati eius minima
            suscipit, optio minus voluptates saepe. Ut commodi ea aperiam animi fugiat harum itaque expedita modi magni
            tenetur magnam maiores exercitationem rerum dignissimos temporibus voluptatum nulla quam, iste quia
            repudiandae
            laudantium. Qui expedita pariatur aspernatur quaerat adipisci aliquid asperiores suscipit accusantium
            mollitia.
        </div>
        <div :class="fontSubcontent">Das ist eine ergänzende Bemerkung</div>

        <div class="education-responsive-title">
            Neue Homepage
        </div>
        <div class="manual-responsive-title">
            Neue Homepage
        </div>
        <div class="nobel-responsive-title">
            Neue Homepage
        </div>
        <div class="default-responsive-title">
            Neue Homepage
        </div>


        <div class="education-responsive-content">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
            aspernatur
        </div>
        <div class="manual-responsive-content">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
            aspernatur
        </div>

        <div class="nobel-responsive-content">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
            aspernatur
        </div>

        <div class="default-responsive-content">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
            aspernatur
        </div>

        <div class="mt-10">
            {{ homepage }}
        </div>




    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {

    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },


    unmounted() {
    },

    data() {
        return {
            homepageStore: null,
        };
    },


    computed: {
        ...mapWritableState(useHomepageStore, ['homepage', 'fontTitle', 'fontSubtitle', 'fontContent', 'fontSubcontent']),
    },

    methods: {



    }

}
</script>
