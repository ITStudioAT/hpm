<template>



    <div class="d-flex flex-row align-center ga-1">
        <v-icon :icon="getFileIcon(file.extension)" :style="'color:' + getFileColor(file.extension) + ' !important;'"
            size="large" class=" text-medium-emphasis" />
        <div class="text-caption">{{ file.name }}</div>
    </div>
    <div class="d-flex flex-row flex-wrap align-center ga-2 font-weight-light">
        <div>
            {{ formatFileSize(file.size) }}
        </div>

        <div class="d-flex flex-row align-center ga-1" v-if="file.width && file.height">

            {{ '[ ' + file.width + ' x ' + file.height + ' ]' }}
        </div>

        <div class="d-flex flex-row align-center ga-1" v-if="file.duration_seconds">
            <v-icon icon="mdi-clock-outline" size="small" />
            {{ formatDuration(file.duration_seconds) }}
        </div>

        <div class="d-flex flex-row align-center ga-1" v-if="file.modified">
            <v-icon icon="mdi-update" size="small" />
            {{ file.modified }}
        </div>
    </div>



</template>


<script>
export default {
    props: ['file'],

    data() {
        return {

        };
    },

    methods: {
        formatFileSize(sizeInBytes) {
            if (sizeInBytes === 0) return '0 B';

            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(sizeInBytes) / Math.log(1024));
            const size = sizeInBytes / Math.pow(1024, i);

            if (i === 0) {
                // Unit is B — show as integer, no decimals
                return `${Math.round(size)} B`;
            } else {
                // Other units — show with 1 decimal, dot as separator
                return `${size.toFixed(1)} ${units[i]}`;
            }
        },

        formatDuration(seconds) {
            const totalSeconds = Math.round(seconds); // round to nearest second
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },


        getFileColor(extension) {
            var color = getComputedStyle(document.documentElement).getPropertyValue('--mm-file-color').trim();

            if (['jpg', 'jpeg', 'png', 'gif', 'heic', 'svg'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-image-color').trim();

            if (['mp4', 'mov', 'avi', 'mkv'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-video-color').trim();

            if (['pdf'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-pdf-color').trim();


            if (['doc', 'docx'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-word-color').trim();


            if (['xls', 'xlsx'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-excel-color').trim();

            if (['ppt', 'pptx'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-powerpoint-color').trim();

            if (['mdb', 'accdb'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-access-color').trim();

            if (['pub'].includes(extension)) color = getComputedStyle(document.documentElement).getPropertyValue('--mm-publisher-color').trim();


            return color;
        },



        getFileIcon(extension) {
            const icons = {
                // Images
                jpg: 'mdi-file-image',
                jpeg: 'mdi-file-image',
                png: 'mdi-file-image',
                gif: 'mdi-file-image',
                heic: 'mdi-file-image',
                svg: 'mdi-file-image',

                // Videos
                mp4: 'mdi-file-video',
                mov: 'mdi-file-video',
                avi: 'mdi-file-video',
                mkv: 'mdi-file-video',

                // Documents
                pdf: 'mdi-file-pdf-box',
                doc: 'mdi-file-word-box',
                docx: 'mdi-file-word-box',
                xls: 'mdi-file-excel-box',
                xlsx: 'mdi-file-excel-box',
                ppt: 'mdi-file-powerpoint-box',
                pptx: 'mdi-file-powerpoint-box',
                txt: 'mdi-file-document-outline',
                csv: 'mdi-file-delimited',

                // Code
                php: 'mdi-language-php',
                js: 'mdi-language-javascript',
                ts: 'mdi-language-typescript',
                html: 'mdi-language-html5',
                css: 'mdi-language-css3',

                // Archives
                zip: 'mdi-folder-zip',
                rar: 'mdi-folder-zip',
                tar: 'mdi-folder-zip',

                // Default
                default: 'mdi-file-outline',
            };

            return icons[extension.toLowerCase()] || icons.default;
        }

    },



}
</script>
