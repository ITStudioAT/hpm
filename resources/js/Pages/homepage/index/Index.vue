<template>
    <v-container fluid class="h-100 w-100 d-flex flex-column align-center justify-center">

        <div>
            <div class="heroTitle">Das ist der Titel </div>
            <div class="heroLead">Das ist der Unteritel</div>

            <div class="title">Das ist der Titel </div>
            <div class="subtitle">Das ist der Unteritel</div>
            <div class="content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
                aspernatur
                numquam culpa esse voluptas. Laudantium reprehenderit minima dolore eum? Hic nam impedit ratione quod
                consequuntur? Illum quae sed perferendis distinctio error vero doloribus nostrum ipsam, praesentium
                facere
                ex
                totam alias adipisci at quod nulla in obcaecati voluptatum aliquid quo optio fugit. Obcaecati eius
                minima
                suscipit, optio minus voluptates saepe. Ut commodi ea aperiam animi fugiat harum itaque expedita modi
                magni
                tenetur magnam maiores exercitationem rerum dignissimos temporibus voluptatum nulla quam, iste quia
                repudiandae
                laudantium. Qui expedita pariatur aspernatur quaerat adipisci aliquid asperiores suscipit accusantium
                mollitia.
            </div>
            <div class="subcontent">Das ist eine ergänzende Bemerkung</div>
        </div>


        <div style="max-width: 500px;">
            <div class="heroTitle">Das ist der Titel </div>
            <div class="heroLead">Das ist der Unteritel</div>

            <div class="title">Das ist der Titel </div>
            <div class="subtitle">Das ist der Unteritel</div>
            <div class="content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur asperiores provident maxime at quam
                aspernatur
                numquam culpa esse voluptas. Laudantium reprehenderit minima dolore eum? Hic nam impedit ratione quod
                consequuntur? Illum quae sed perferendis distinctio error vero doloribus nostrum ipsam, praesentium
                facere
                ex
                totam alias adipisci at quod nulla in obcaecati voluptatum aliquid quo optio fugit. Obcaecati eius
                minima
                suscipit, optio minus voluptates saepe. Ut commodi ea aperiam animi fugiat harum itaque expedita modi
                magni
                tenetur magnam maiores exercitationem rerum dignissimos temporibus voluptatum nulla quam, iste quia
                repudiandae
                laudantium. Qui expedita pariatur aspernatur quaerat adipisci aliquid asperiores suscipit accusantium
                mollitia.
            </div>
            <div class="subcontent">Das ist eine ergänzende Bemerkung</div>
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
