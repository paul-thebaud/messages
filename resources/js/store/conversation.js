import api from '../helpers/api';

const TYPES = {
    FETCH: 'FETCH',
    USE: 'USE'
};

const state = {
    conversations: [],
    conversation: null,
    messages: []
};

const mutations = {
    [TYPES.FETCH](state, conversations) {
        state.conversations = conversations;
    },
    [TYPES.USE](state, { conversation, messages }) {
        state.conversation = conversation;
        state.messages     = messages;
    }
};

const getters = {
    conversations: state => state.conversations,
    conversation: state => state.conversation,
    messages: state => state.messages
};

const actions = {
    index({ commit }) {
        return new Promise(resolve => {
            api.index('conversations')
                .then((conversations) => {
                    commit(TYPES.FETCH, conversations);
                    resolve(conversations);
                });
        });
    },

    show({ commit }, conversationId) {
        return new Promise(resolve => {
            api.show('conversations', conversationId)
                .then((conversation) => {
                    api.index(`conversations/${conversation.id}/messages`)
                        .then((messages) => {
                            commit(TYPES.USE, { conversation, messages });
                            resolve({ conversation, messages });
                        });
                });
        });
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
};
