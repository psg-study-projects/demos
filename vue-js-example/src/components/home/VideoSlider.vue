<template>
<div v-if="!isPreviewVisible" id="wrap-video_slider" class="template-wrap">

    <b-row id="playerSection">
        <b-col>

            <h2><img src="@/assets/img/gtc-logo-bug.png" class="img-fluid" alt="GTC Logo"> <span>Video</span></h2>

            <!-- Captions: header & text -->
            <div class="superbox-header OFF-d-none d-md-block">
                <transition name="t_video_slider_header" >
                    <h3 v-if="isCaptionVisible" class="">{{ videos[slideIndex].title }}</h3>
                </transition>
            </div>
            <div class="superbox-text OFF-d-none d-md-block">
                <transition name="t_video_slider_subtitle" >
                    <p v-if="isCaptionVisible" class="tag-subtitle">{{ videos[slideIndex].details.subtitle }}</p>
                </transition>
                <transition name="t_video_slider_description" >
                    <p v-if="isCaptionVisible" class="tag-description">{{ videos[slideIndex].details.description }}</p>
                </transition>
            </div>

            <b-carousel id="carousel1"
                        controls
                        background="#000"
                        :interval="1114000"
                        v-model="slideIndex"
                        @sliding-start="onSlideStart"
                        @sliding-end="onSlideEnd" >
                <b-carousel-slide v-for="vid in videos" :key="vid.id" 
                                  :id="'slide-'+vid.id" 
                                  class="superbox-slide">
                    <div slot="img" class="OFF-img-fluid crate-video">
                        <video class="" controls>
                            <source :src="vid.details.videomp4" type="video/mp4">
                            <!--
                            <source src="movie.mp4" type="video/mp4">
                            <source src="movie.ogg" type="video/ogg">
                            -->
                            Your browser does not support the video tag.
                        </video> 
                    </div>
                </b-carousel-slide>

            </b-carousel>
        </b-col>
    </b-row>

    <b-row id="playlistSection" class="no-gutters">
        <b-col v-for="vid in videos" :key="vid.id" :id="'thumbnail-'+vid.id" class="superbox-thumbnail">
            <img v-on:click="queueVideo($event, vid.id)" class="OFF-img-fluid" v-bind:src="vid.thumb">
        </b-col>
    </b-row>

</div>
</template>

<script>
// https://stackoverflow.com/questions/50317276/fitting-video-in-slider-bootstrap-4-0
// https://mdbootstrap.com/vue/advanced/carousel/
// https://stackoverflow.com/questions/11914716/bootstrap-carousel-other-content-than-image
// https://getbootstrap.com/docs/4.0/components/carousel/
// https://stackoverflow.com/questions/11757537/css-image-size-how-to-fill-not-stretch

//import { mapGetters } from 'vuex';

export default {
    name: 'VideoSlider',
    props: {
        msg: String
    },
    created() {
        this.$store.dispatch('getVideos',this.videosToDisplay);
    },
    mounted () {
        //this.isCaptionVisible = true;
    },
    computed: {
        isPreviewVisible() { // %FIXME: remove (tho we may still need this in this component?)
            return this.$store.getters.isPreviewVisible;
        },
        videos() {
            // Delay showing caption true until after videos[] is populated, aka...
            // Only show caption if videos is non-empty
            //   ~ hacky but it works
            let result = this.$store.getters.videos;
            this.isCaptionVisible = (result!==undefined) && (result.length > 0);
            return result;
            //return this.$store.getters.videos;
        }
        /*
        ...mapGetters([
            'videos' // map this.videos to this.$store.getters.videos
        ])
        */
    },
    data () {
        return {
            isCaptionVisible: false, 
            slideIndex: 0,
            videosToDisplay: 5,
            sliding: null
        }
    },
    methods: {
        queueVideo (e, vidID) {
            e.preventDefault();
            this.slideIndex = this.videos.findIndex( (o) => o.id===vidID );
        },
        onSlideStart (slide) {
            //console.log('onSlideStart');
            this.sliding = true;
            this.isCaptionVisible = false;
        },
        onSlideEnd (slide) {
            //console.log('onSlideEnd');
            this.sliding = false;
            this.isCaptionVisible = true;
        },
    },
}
</script>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

#wrap-video_slider {
    margin-top: 3rem;
    height: 100vh;
}
#playlistSection img {
    cursor: pointer;
    width: 90%;
}

.crate-video {
    line-height: 0;
    overflow: hidden;
}
@include media-breakpoint-up(xs) {
    #wrap-video_slider {
        height: inherit;
    }
    video {
        width: 200%;
        object-fit: cover; // fill, contain, scale-down
        height: inherit;

        margin-left: -50%;
        /*
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        */
    }
}

@include media-breakpoint-up(md) {
    #wrap-video_slider {
        height: 100vh;
    }
    video {
        width: 100%;
        height: 80vh;
        margin-left: 0;
    }
}

@include media-breakpoint-up(lg) {
}

// ----

#playerSection > .col {
    position: relative;
}

// --- Component Headers ---

// %FIXME: DRY
h2 {
    z-index: 200;
    position: absolute;
    text-transform: uppercase;
    justify-content: center;
    display: flex;
    align-items: center;
}
// Responsive breakpoints
@include media-breakpoint-up(xs) {
    h2 {
        top: 0;
        left: 2rem;
    }
    h2 > img {
        margin-right: 0.3em;
        width: 2.1em;
        font-size: 0.50em;
    }
    h2 > span {
        font-size: 0.40em;
    }
}
@include media-breakpoint-up(md) {
    h2 {
        top: 1rem;
        left: 2.5rem;
    }
    h2 > img {
        margin-right: 0.3em;
        width: 3.2em;
        font-size: 0.8em;
    }
    h2 > span {
        font-size: 0.8em;
    }
}

// --- Video Player Captions + Text ---

#playerSection .superbox-header {
    z-index: 200;
    position: absolute;
}
#playerSection .superbox-header h3 {
    text-transform: uppercase;
}
#playerSection .superbox-text {
    z-index: 200;
    position: absolute;
}
#playerSection .superbox-text p {
    //max-width: 30%;
}
#playerSection .superbox-text p.tag-subtitle {
    text-transform: uppercase;
}
// Responsive breakpoints
@include media-breakpoint-up(xs) {
    #playerSection .superbox-header {
        top: 2rem;
        left: 2rem;
    }
    #playerSection .superbox-header h3 {
        font-size: 2em;
    }
    #playerSection .superbox-text {
        bottom: 1.7rem; //6rem;
        left: 2rem;
        right: 2rem;
    }
    #playerSection .superbox-text p {
        margin-bottom: 0;
        min-width: 10rem;
        //width: 95%;
    }
    #playerSection .superbox-text p.tag-subtitle {
        font-size: 1em;
    }
    #playerSection .superbox-text p.tag-description {
        line-height: 1.2;
        font-size: 1.25em;
    }
}
@include media-breakpoint-up(md) {
    #playerSection .superbox-header {
        top: 6rem;
        left: 2.5rem;
    }
    #playerSection .superbox-header h3 {
        font-size: 4em;
    }
    #playerSection .superbox-text {
        bottom: 6.5rem;
        left: 2.5rem;
        right: 2.5rem;
    }
    #playerSection .superbox-text p {
        min-width: 10rem;
        width: 37%;
    }
    #playerSection .superbox-text p.tag-subtitle {
        margin-bottom: 1em;
        font-size: 2em;
    }
    #playerSection .superbox-text p.tag-description {
        font-size: 2.5em;
    }
}


// --- Transitions ---

.t_video_slider_header-enter-active,
.t_video_slider_header-leave-active {
  transition: all 0.6s;
}
.t_video_slider_header-leave-to {
  opacity: 0;
  transform: translateY(-3rem);
}
.t_video_slider_header-enter {
  opacity: 0;
  transform: scale(0.3);
}

.t_video_slider_subtitle-enter-active,
.t_video_slider_subtitle-leave-active {
  transition: all 0.6s;
}
.t_video_slider_subtitle-leave-to {
  opacity: 0;
  transform: translateY(3rem);
}
.t_video_slider_subtitle-enter {
  opacity: 0;
  transform: scale(0.3);
}

.t_video_slider_description-enter-active,
.t_video_slider_description-leave-active {
  transition: all 0.6s;
}
.t_video_slider_description-leave-to {
  opacity: 0;
  transform: translateY(3rem);
}
.t_video_slider_description-enter {
  opacity: 0;
  transform: scale(0.3);
}


</style>
