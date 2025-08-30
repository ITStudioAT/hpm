<template>


    <v-app-bar :color="header.structure.props.color" :border="header.structure.props.border"
        :density="!header.structure.props.height ? header.structure.props.density : ''"
        :height="header.structure.props.height >= 24 ? header.structure.props.height : undefined"
        :elevation="header.structure.props.elevation" :flat="header.structure.props.flat"
        :scroll-behavior="header.structure.props.scroll_behavior"
        v-if="index && index.structure.header.is_visible && header">
        APPBAR
    </v-app-bar>

    <v-img src="storage/images/motiv.jpg" height="1024" width="1920" />


    <v-main>
        <v-container class=" h-100 d-flex flex-column align-center justify-center main">
            <div>{{ homepage }}</div>
            <div>{{ index }}</div>
            <div>Text im Main</div>
            <div class="heroTitle">HeroTitle</div>
            <div class="heroLead">HeroLead</div>

            <div class="first">
                <div class="heroTitle">HeroTitle bg-first</div>
                <div class="heroLead">HeroLead bg-first</div>
            </div>

            <div class="second">
                <div class="heroTitle">HeroTitle bg-second</div>
                <div class="heroLead">HeroLead bg-second</div>
            </div>

            <div class="third">
                <div class="heroTitle">HeroTitle bg-second</div>
                <div class="heroLead">HeroLead bg-second</div>
            </div>


            <!-- IMAGE -->
            <v-card height="800" class="overflow-hidden w-100 my-10">
                <v-img src="storage/images/motiv.jpg" height="100%" cover position="center calc(50% + 500px)">
                    <!-- Overlay -->
                    <div class="d-flex flex-column justify-end align-start w-100 h-100 pa-4"
                        style="background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,.55) 100%);">
                        <div class="text-white text-h5 font-weight-bold">TEXT ÜBER DEM IMAGE</div>
                        <div class="mt-2">
                            <v-btn color="primary" variant="flat" class="mr-2" href="/dein-link">Zum Link</v-btn>
                            <v-btn color="white" variant="outlined" @click="() => { }">Aktion</v-btn>
                        </div>
                    </div>
                </v-img>
            </v-card>

            <div v-for="i in 10">
                <div style="width: 600px;" class="mt-10">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ea alias sed aut. Labore, odio,
                    praesentium
                    delectus tenetur illum a molestiae id deleniti magni dicta tempore illo aut sapiente nobis
                    commodi
                    accusantium architecto aspernatur debitis asperiores voluptates perspiciatis blanditiis
                    excepturi
                    ab?
                    Consequuntur, quae debitis recusandae, earum voluptas ea nobis quam unde corrupti quasi porro
                    sunt
                    dolorum nisi optio non sed deleniti hic beatae ex blanditiis molestiae adipisci? Tempore
                    expedita
                    ipsam
                    sint aperiam accusamus, eos fugiat nihil facilis quis quam ratione optio ut nobis totam
                    explicabo,
                    quo
                    voluptate reiciendis placeat? Porro esse aliquam facilis error vel maiores doloremque tenetur
                    cum
                    labore
                    aperiam.
                </div>
            </div>




        </v-container>
    </v-main>
    <!--
        <v-footer color="surface-light" name="footer" app>
            Footer
        </v-footer>
        -->

</template>

<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {
    async beforeMount() {
        this.homepageStore = useHomepageStore();
        const homepage_id = this.homepage.id;

        const index_id = this.homepage.structure.index.id;
        await this.homepageStore.loadRecord(homepage_id, index_id);
        this.index = JSON.parse(JSON.stringify(this.record));

        const header_id = this.index.structure.header.id;
        await this.homepageStore.loadRecord(homepage_id, header_id);
        this.header = JSON.parse(JSON.stringify(this.record));

        const footer_id = this.index.structure.footer.id;
        await this.homepageStore.loadRecord(homepage_id, footer_id);
        this.footer = JSON.parse(JSON.stringify(this.record));


    },

    data() {
        return {
            homepageStore: null,
            index: null,
            header: null,
            footer: null,
        };
    },

    computed: {
        // Kept for debugging; typography comes from CSS classes (.heroTitle, .content, ...)
        ...mapWritableState(useHomepageStore, ["homepage", "record"]),
    },
};
</script>
