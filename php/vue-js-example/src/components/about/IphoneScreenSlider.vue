<template>
    <transition-group name='fade' tag='div'>
        <div v-for="number in [currentNumber]" :key='number'>
            <img :src="currentImage" alt="phone screen" class="img-fluid"/>
        </div>
    </transition-group>
</template>

<script>
// https://jsfiddle.net/czbLyn8h/
export default {
    name: 'IphoneScreenSlider',
    props: {
        msg: String
    },
    computed: {
    },
    data () {
        return {
            images: [
                require('./assets/img/98815-NH_Screen.jpg'),
                require('./assets/img/99e41-ig-browse.jpg'),
                require('./assets/img/987d9-text-block.jpg')
            ],
            currentNumber: 0,
            timer: null
        }
    },
    mounted: function () {
        this.startRotation();
    },
    computed: {
    	currentImage: function() {
      	    return this.images[Math.abs(this.currentNumber) % this.images.length];
        }
    },
    methods: {
        startRotation: function() {
            this.timer = setInterval(this.next, 3000);
        },
        
        next: function() {
            this.currentNumber += 1
        }
    }
}
</script>

<style scoped lang="scss">
img {
    //transform: scale(1.01);
}
.fade-enter-active, .fade-leave-active {
    //transition: all 0.8s ease;
    transition: opacity 0.8s ease;
    overflow: hidden;
    visibility: visible;
    opacity: 1;
    position: absolute;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
    visibility: hidden;
}
</style>
