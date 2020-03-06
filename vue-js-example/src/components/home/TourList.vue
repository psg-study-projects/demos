<template>
    <b-container fluid id="wrap-home_tourlist" class="debug-border template-wrap">

        <b-row class="OFF-h-100">
            <b-col class="OFF-h-100">

                <transition-group id="artistSection" class="grid row" name="t_artist_grid" tag="div">
                    <b-col :md="gridItemWidth(item.format)" v-for="item in grid" v-if="!isPreviewVisible" :key="item.id" :id="item.id" class="OFF-col-xs-12 OFF-col-md-4 grid__item tour-list--item">
                        <div v-on:click="renderPreview($event, item.id)" class="crate">
                            <div class="background-image" :style="`background-image: url(${item.details.img})`"></div>
                            <a href="#" class="img-wrap">
                                <img class="" v-bind:src="item.details.img">
                            </a>
                            <div class="item-title">
                                <h6 class="item-sub">{{item.details.tour}}</h6>
                                <h3 class="item-heading">{{item.details.name}}</h3>
                                <b-button variant="danger" class="tag-clickme_to_view_work"><plus-icon /><span>View Work</span></b-button>
                            </div>
                        </div>
                    </b-col>
                </transition-group>
        
                <transition name="t_preview">
                    <b-row v-if="isPreviewVisible" key="k_preview" id="previewSection" class="preview justify-content-sm-center">
                        <b-col class="col-12">
                            <button v-on:click="closePreview" class="tag-clickme_to_close_preview"><close-icon /><span class="text-hidden">Close</span></button>
                            <TourPreview :pdata="preview" />
                        </b-col>
                    </b-row>
                </transition>

            </b-col>
        </b-row>

    </b-container>
</template>

<script>

//import TourPreview from '@/home/components/TourPreview.vue'
import TourPreview from './TourPreview.vue'
import CloseIcon from "vue-material-design-icons/close.vue"
import PlusIcon from "vue-material-design-icons/plus.vue"
import EventBus from '@/main.js';

//import gridfx from '../gridfx'
//Object.defineProperty(Vue.prototype, '$gridfx', { value: gridfx });

// https://forum.vuejs.org/t/data-set-in-created-is-not-available-in-mounted/5555

// https://vuejs.org/v2/guide/transitions.html#Transitioning-Between-Elements

// => https://css-tricks.com/intro-to-vue-5-animations/

// => https://vuejs.org/v2/guide/transitions.html#CSS-Transitions

// https://alligator.io/vuejs/understanding-transitions/

// Thumbnails / grid :
// ~ https://getbootstrap.com/docs/3.3/components/#thumbnails
// ~ also see: 
//   o https://masonry.desandro.com/
//   o => https://isotope.metafizzy.co/
//      - https://www.npmjs.com/package/vueisotope
//   o https://salvattore.js.org/

// Use velocity.js instead??
//   ~ => http://velocityjs.org/#fade
//   ~ http://velocityjs.org/#bugs
//   ~ https://gist.github.com/edwardlorilla/f112d36af71094280ae88032756ac61f
//   ~ https://medium.com/vue-mastery/how-to-create-vue-js-transitions-6487dffd0baa
//   ~ https://codepen.io/macsupport/pen/xbWGZo
//   ~ https://www.smashingmagazine.com/2014/09/animating-without-jquery/
//   ~ 
export default {
    name: 'TourList', // aka 'Tourid'
    computed: {
        grid() {
            return this.$store.getters.grid;
        },
        tours() {
            return this.$store.getters.tours;
        },
        /*
        ...mapGetters([
            'tours' // map this.tours to this.$store.getters.tours
        ])
        */
    },
    data() {
        return {
            isPreviewVisible : false,
            preview: null,
            gridItemsToDisplay: 6,
            t: null
        }
    },
    methods: {
        gridItemWidth(format) {
            switch (format.width) {
                case 'column-one-third':
                    return 4;
                case 'column-one-half':
                    return 6;
                default: 
                    return 4;
            }
        },
        renderPreview(e, guid) { // aka openPreview
            e.preventDefault();
            console.log('clicked: '+guid);
            //window.scrollTo(0,0);
            let selected = this.grid.find( (t) => t.id===guid );
            this.preview = selected;
            this.isPreviewVisible = true;
            EventBus.$emit('open-preview');
        },
        closePreview(e) {
            e.preventDefault();
            EventBus.$emit('close-preview');
            this.isPreviewVisible = false;
        },
    },
    created() {
        this.$store.dispatch('getGrid',this.gridItemsToDisplay);
    },
    props: {
        msg: String
    },
    components: {
        TourPreview,
        CloseIcon,
        PlusIcon
    }
}
</script>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

// Responsive breakpoints
@include media-breakpoint-up(xs) {
    #previewSection {
        position: absolute;
    }
    button.tag-clickme_to_close_preview {
        position: fixed;
        z-index: 150;
        top: 0;
        left: 0.5em;
        border: none;
        background: none;
        color: #fff;
        padding: 0;
        margin: 0;
    }
    button.tag-clickme_to_close_preview > .close-icon.material-design-icon {
        font-size: 5.1em;
    }
}

@include media-breakpoint-up(sm) {
    button.tag-clickme_to_close_preview {
        top: 0;
        left: inherit;
        right: 0.5em;
    }
}
@include media-breakpoint-up(md) {
    #previewSection {
        // see: https://stackoverflow.com/questions/28144233/bootstrap-container-with-positionabsolute-loses-layout-inside
        position: fixed;
        height: 100vh;
    }
}

@include media-breakpoint-up(lg) {
}

html, body {
    height: 100% !important;
}
#wrap-home_tourlist > div.row > div.col {
    position: relative;
    min-height: 100vh;
}
#previewSection {
    // see: https://stackoverflow.com/questions/28144233/bootstrap-container-with-positionabsolute-loses-layout-inside
    //position: fixed; // absolute;
    top: 0;
    left: 0;
    right: 0;
    //height: 100vh;
}
ul {
    list-style: none;
}
img {
    width: 100%;
}

.tour-list--item .crate {
    margin-bottom: 2em;
    position: relative;
    overflow: hidden;
}

.tour-list--item .img-wrap,
.tour-list--item .item-title {
    position: relative;
}

.item-title {
    padding: 1.21rem; // 20px;
    background: rgba(50, 50, 50, .7);
    //margin-top: -0.15rem; // -2px;
    //min-height: 8rem; // 130px;
}
.item-title .item-sub,
.item-title .item-heading {
    text-transform: uppercase;
}
.item-title .item-sub {
    color: red;
}

.background-image {
    filter: blur(15px);
    width: 100%;
    height: 100%;
    position: absolute;
    background-position: center;
    transform: scale(1.3);
}
button.tag-clickme_to_view_work .material-design-icon.plus-icon {
    display: none;
}

#previewSection > div.col {
    position: relative;
}
button.tag-clickme_to_close_preview .material-design-icon {
    font-size: 3.9em; // 60px;
    cursor: pointer;
}

// === Transitions ===

// Grid
.t_artist_grid-enter-active {
  transition: all 0.7s;
}
.t_artist_grid-leave-active {
  transition: all 0.7s;
}
.t_artist_grid-enter, 
.t_artist_grid-leave-to {
  opacity: 0;
  transform: scale(0.5);
}

// Preview
//   %NOTE %FIXME 20180813 -- this transition is not working b/c, I believe, it is triggered in the Preview component
//    ...not sure why the computed property doesn't catch it however...
//    solution is to move it to absolute position so it overlaps??
//    it's there, jsut hidden by the grid content becoming visible (??)
// Ideally we are able to somehow hook to other elements (timing and synchronization), if not may just have to combine the 2 and make the preview
//   position absolute
// ==
/*
        isPreviewVisible() {
            return this.$store.getters.isPreviewVisible;
        },
isPreviewVisible should not be a getter, polluted state in Vuex state
    ~ probably should revert back to just using a custom event 
    ~ also, can put X in parent instead of preview child, that solves alot of problems (no event needed?)
isPreviewVisible() {
    // something like this (psuedo-code)
    if (transition from not visible to visible) {
        hide grid first, then using promise or equiv show preview
    } else if (transition from visible to not visible) {
        hide preview first, then using promise or equiv show grid
    }
},

*/
.t_preview-enter-active {
  transition: all 0.8s;
}
.t_preview-leave-active {
  transition: all 0.8s;
}
.t_preview-enter, 
.t_preview-leave-to {
  opacity: 0;
  transform: scale(0.5);
}

</style>
