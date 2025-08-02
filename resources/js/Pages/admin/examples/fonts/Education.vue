<template>
    <v-container fluid class="pa-6">
        <v-card width="1280" height="600" color="primary">
            <v-card-title> Desktop </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="4" :class="bg_light">
                        <div class="education-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="education-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="education-desktop-content">
                            {{ content }}
                        </div>
                        <div class="education-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="4" :class="bg_dark">
                        <div class="education-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="education-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="education-desktop-content">
                            {{ content }}
                        </div>
                        <div class="education-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <v-card width="400" height="1000" color="primary" class="mt-4">
            <v-card-title> Handy </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="12" :class="bg_light">
                        <div class="education-handy-title">
                            {{ heading }}
                        </div>
                        <div class="education-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="education-handy-content">
                            {{ content }}
                        </div>
                        <div class="education-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="12" :class="bg_dark">
                        <div class="education-handy-title">
                            {{ heading }}
                        </div>
                        <div class="education-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="education-handy-content">
                            {{ content }}
                        </div>
                        <div class="education-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            heading: "Fotosynthese – Lebensgrundlage der Pflanzen",
            subtitle:
                "Diese Informationen sind ganz besonders wichtig für Ihre Gesundheit",
            content:
                "Die Fotosynthese ist ein biochemischer Prozess, bei dem grüne Pflanzen mithilfe von Chlorophyll, Sonnenlicht, Wasser und Kohlendioxid energiereiche Glukose produzieren. Dabei entstehen als Nebenprodukt Sauerstoff und Wasser, die für viele Lebewesen unverzichtbar sind. Im Biologieunterricht untersuchen wir sowohl die Licht- als auch die Dunkelreaktionen und führen Experimente mit Wasserpflanzen durch, um die Sauerstoffbildung sichtbar zu machen.",
            subcontent:
                "*) Bitte beachten Sie unbedingt: Die Preise verstehen sich pro Person und Nacht.",

            bg_light: "bg-brown-lighten-5",
            bg_dark: "bg-brown-darken-4",
        };
    },
};
</script>
