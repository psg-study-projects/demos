<template>
    <b-container fluid id="wrap-home_tourpreview" class="h-100 debug-border template-wrap">

        <b-row id="preview" class="justify-content-center h-100">

            <b-col md="5" id="supercrate-img" class="my-auto">
                <div class="wrap-background-image">
                    <div class="gradient-image"></div>
                    <div class="background-image" :style="`background-image: url(${pdata.details.img})`"></div>
                </div>
                <img id="img-primary" rel="canonical" class="OFF-align-self-center img-fluid" v-bind:src="pdata.details.img">
                <!--
                <h1>1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111</h1>
                -->
            </b-col>

            <b-col md="7" id="supercrate-info" class="my-auto">
                <div class="OFF-media-body align-self-center">
                    <b-row id="row-header">
                        <b-col>
                            <h5 class="item-title">{{pdata.details.tour}}</h5>
                            <h2 class="item-artist">{{pdata.details.name}}</h2>
                        </b-col>
                    </b-row>
                    <b-row id="row-media">
                        <b-col md="3" class="crate-media">
                            <div class="cat-header">
                                <!--
                                -->
                                <div class="cat-icon">
                                    <img class="mic" src="@/assets/img/icons8-Microphone-100-white.png">
                                </div>
                                <h6>Radio</h6>
                            </div>
                            <ul id="list-radio" class="media-links flex-col">
                                <li v-on:click="showMediaModal($event, 'audio', 'generic')" id="audioGeneric">
                                    <h4>:30 &#8212; Generic</h4>
                                </li>
                                <li v-on:click="showMediaModal($event, 'audio', 'amex')" id="audioAmex">
                                    <h4>:30 &#8212; Amex</h4>
                                </li>
                                <li v-on:click="showMediaModal($event, 'audio', 'citi')" id="audioCiti">
                                    <h4>:30 &#8212; Citi</h4>
                                </li>
                            </ul>
                        </b-col>
                        <b-col md="3" class="crate-media">
                            <div class="cat-header">
                                <div class="cat-icon"><img class="monitor" src="@/assets/img/icons8-Monitor-100-white.png"></div>
                                <h6>TV</h6>
                            </div>
                            <ul id="list-video" class="media-links flex-col">
                                <li v-on:click="showMediaModal($event, 'video', 'generic')" id="videoGeneric">
                                    <h4>:30 &#8212; Generic</h4>
                                </li>
                                <li v-on:click="showMediaModal($event, 'video', 'amex')" id="videoAmex">
                                    <h4>:30 &#8212; Amex</h4>
                                </li>
                                <li v-on:click="showMediaModal($event, 'video', 'citi')" id="videoCiti">
                                    <h4>:30 &#8212; Citi</h4>
                                </li>
                            </ul>
                        </b-col>
                        <b-col md="3" class="crate-media">
                            <div class="cat-header">
                                <div class="cat-icon"><img class="resolution" src="@/assets/img/icons8-Resolution-100-white.png"></div>
                                <h6>Art</h6>
                            </div>
                            <ul id="list-document" class="media-links flex-col">
                                <li v-on:click="showMediaModal($event, 'document', 'webbanner')" id="documentWebanner">
                                    <h4>Web Banner</h4>
                                </li>
                                <li v-on:click="showMediaModal($event, 'document', 'social')" id="documentSocial">
                                    <h4>Social</h4>
                                </li>
                            </ul>
                        </b-col>
                    </b-row>
                </div>

            </b-col>
        </b-row>

    <!-- media modal -->
    <b-modal id="modal-media-tour"
             ref="mediaModalTour"
             size="lg"
             centered
             :hide-header=true
             :hide-footer=true
             body-bg-variant="light"
             body-text-variant="dark">
        <MediaModal :mdata="mdata" @close_modal="closeMediaModal" />
    </b-modal>

    </b-container>
</template>

<script>

import MediaModal from '@/components/MediaModal.vue'
import MicrophoneIcon from "vue-material-design-icons/microphone.vue"
import PencilIcon from "vue-material-design-icons/pencil.vue"
import PlayCircleOutlineIcon from "vue-material-design-icons/play-circle-outline.vue"

export default {
    name: 'TourPreview',
    computed: {
        mdata() {
            let obj = {
                "mtype": this.modalMediaType,
                "title": this.pdata.details.tour,
                "artworkTitle": null,
                "artworkURL": null,
                "videoTitle": null,
                "videoURL": null,
                "audioTitle": null,
                "audioURL": null
            }
            //obj.title = this.pdata.details.tour;

            switch (this.modalMediaType) {
                case 'video':
                    switch (this.modalMediaBrand) {
                        case 'generic':
                            obj.videoURL = this.pdata.details.video[0].url;
                            break;
                        case 'amex':
                            obj.videoURL = this.pdata.details.video[1].url;
                            break;
                        case 'citi':
                            obj.videoURL = this.pdata.details.video[2].url;
                            break;
                    }
                    break;
                case 'audio':
                    switch (this.modalMediaBrand) {
                        case 'generic':
                            obj.audioURL = this.pdata.details.audio[0].url;
                            break;
                        case 'amex':
                            obj.audioURL = this.pdata.details.audio[1].url;
                            break;
                        case 'citi':
                            obj.audioURL = this.pdata.details.audio[2].url;
                            break;
                    }
                    break;
                case 'document':
                    switch (this.modalMediaBrand) {
                        case 'webbanner':
                            obj.artworkURL = this.pdata.details.artwork[0].url;
                            break;
                        case 'social':
                            obj.artworkURL = this.pdata.details.artwork[1].url;
                            break;
                    }
                    break;
            }
            return obj;
        }, // mdata()
    },

    props: {
        pdata: Object,
    },

    data() {
        return {
            modalMediaBrand: null,
            modalMediaType: null,
        }
    },
    methods: {
        showMediaModal (e, mediaType, mediaBrand) {
            e.preventDefault();
            this.modalMediaType = mediaType;
            this.modalMediaBrand = mediaBrand;
            this.$refs.mediaModalTour.show();
        },
        closeMediaModal() {
            this.$refs.mediaModalTour.hide();
        },
    },
    components: {
        MicrophoneIcon,
        MediaModal,
        PencilIcon,
        PlayCircleOutlineIcon,
    }
}
</script>

<style lang="scss">

/*
.modal-content,
.modal-body {
    background-color: inherit;
    border: none !important;
}
*/
</style>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

#supercrate-info,
#supercrate-img {
    position: relative;
    z-index: 100;
}
#supercrate-img img {
    position: relative;
    z-index: 100;
}
.gradient-image {
    background-image: linear-gradient(to right, rgba(0,0,0,0) 50%, rgba(0,0,0,1));
    /*
    background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(0,0,0,1));
    background-image: -moz-linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
    background-image: -ms-linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
    background-image: -o-linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
    background-image: -webkit-linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
    */
    z-index: 2;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
}
.wrap-background-image {
    z-index: 2;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    transform: scale(1.9);
    transform-origin: center left;
}
.background-image {
    z-index: 1;
    display: relative;
    opacity: 0.2;
    //background-size: cover;
    background-position: left center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;

}

// Responsive breakpoints
@include media-breakpoint-up(xs) {
    img#img-primary {
        padding: 0;
        width: 50em;
    }
    .cat-header > * {
    }
    #supercrate-img img {
        margin-bottom: 1em;
    }
    #row-media .crate-media {
        margin-top: 0.7em;
    }
}

@include media-breakpoint-up(md) {
    img#img-primary {
        padding: 0 0 0 4em;
    }
    #supercrate-img img {
        margin-bottom: 0;
    }
    #row-media {
        margin-top: 2.7em;
    }
    #row-media .crate-media {
        margin-top: 0;
    }
}

@include media-breakpoint-up(lg) {
}

img.mic, img.monitor, img.resolution {
    width: 3.5em;
}
ul {
    list-style: none;
    padding-left: 0;
    margin-top: 1rem;
}
ul.media-links > li {
    cursor: pointer;
    margin-top: 1.2em;
}

button {
    text-align: center;
    text-transform: uppercase;
}
h4 {
    font-size: 1.2em;
}
h5 {
    color: red;
    font-weight: bold;
}
.cat-header > * {
    display: inline-block;
}
.cat-icon {
    margin-right: 1.3em;
}


</style>
