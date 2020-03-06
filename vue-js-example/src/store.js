import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

// %TODO: may want to break this up like in the shopping cart example?

Vue.use(Vuex)

export default new Vuex.Store({

    state: {
        details: [],
        search: [], // search results
        //tours: [],
        grid: [],
        videos: [],
        featuredVideos: []
    },

    mutations: {
        UPDATE_SEARCH (state, payload) {
            state.search = payload;
        },
        UPDATE_DETAILS (state, payload) {
            state.details = payload;
        },
        /*
        UPDATE_TOURS (state, payload) {
            state.tours = payload;
        },
        */
        UPDATE_GRID (state, payload) {
            state.grid = payload;
        },
        UPDATE_VIDEOS (state, payload) {
            state.videos = payload;
        },
    
    },

    actions: {
        getSearch({ commit }, queryString, take) {
            // http://www.globaltourcreatives.com/api/?get=search&var=britney
            let url = 'http://www.globaltourcreatives.com/api/?get=search&var='+queryString;
            axios.get(url).then( (response) => {
                commit('UPDATE_SEARCH', response.data);
            });
        },
        getDetails({ commit }, tourID) { // specifically *tour* details
            // http://www.globaltourcreatives.com/api/?get=details&tourID=fBSfJJ
            let url = 'http://www.globaltourcreatives.com/api/?get=details&tourID='+tourID;
            axios.get(url).then( (response) => {
                commit('UPDATE_DETAILS', response.data);
            });
        },
        /*
        getTours({ commit }, take) {
            // /api/tours
            axios.get('/api/tours?take='+take).then( (response) => {
                commit('UPDATE_TOURS', response.data);
            });
        },
        */
        getGrid({ commit }, take) {
            // /api/grid
            axios.get('/api/grid?take='+take).then( (response) => {
                commit('UPDATE_GRID', response.data);
            });
        },
        getVideos({ commit }, take) {
            // /api/videos
            axios.get('/api/videos?take='+take).then( (response) => {
                commit('UPDATE_VIDEOS', response.data);
            });
        },
    },

    getters: {
        //search: state => state.search,
        search: state => {
            //state.search.pop(); // workaround for api response format: drop last item in array (the meta data)
            return state.search;
        },
        details: state => state.details,
        /*
        tours: state => state.tours,
        */
        //grid: state => state.grid,
        grid: state => {
            return state.grid.slice(0,6); // only return first 6
        },
        videos: state => state.videos,

        /*
        detailsNice: state => {
            return {
                "tourName" : (undefined !== state.details[0].tourName) ? state.details[0].tourName : null,
                "radiospotUrl" : state.details[2].radioSpots[0].spotURL
            };
        },
        detailsTourName: state => {
            return state.details[0].tourName;
        },
        detailsSpotURL: state => {
            return state.details[2].radioSpots[0].spotURL;
        }
        */
    }
})
/* example api response (mdata),
   for request: http://www.globaltourcreatives.com/api/?get=details&tourID=PjRhin
[
    {
	    "tourID": "PjRhin",
	    "tourName": "Brit Floyd 2018"
    }, 
    {
	    "tvSpots": null
    }, 
    {
	    "radioSpots": [
            {
		        "spotTitle": "Generic",
		        "spotDuration": "30",
		        "spotURL": "http:\/\/d17hws2m7av7qo.cloudfront.net\/media\/revisions\/new\/WW5YPI.mp3"
	        }
        ]
    }, 
    {
	    "printItems": [
            {
		        "itemTitle": "Key Art Package",
		        "thumbnailURL": "http:\/\/www.globaltourcreatives.com\/media\/revisions\/?token=ac3643eed37839c218f0dafc047dd2d1&seed=76b34b3009fd1c5c1aee72c73e286c45"
	        }
        ]
    }, 
    {
	    "Owner": "Gobal Tour Creatives (Ireland) Limited",
	    "API Version": "beta",
	    "Contact": "Russell Treacy - +1.310.982.2820"
    }
]
*/
