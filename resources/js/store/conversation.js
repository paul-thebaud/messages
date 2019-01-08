import api from '../helpers/api';

const TYPES = {
    FETCH: 'FETCH',
    USE: 'USE',
    CREATE: 'CREATE'
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
    },
    [TYPES.CREATE](state, message) {
        state.messages.push(message);
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
                    conversations.forEach((conversation)=>{
                        Echo.join(`App.Conversation.${conversation.id}`)
                            .listen('newMessage',()=>{console.log('test')});
                    });
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
    },

    create({commit}, message){
        return new Promise(resolve => {
            api.store(`conversations/${message.conversation_id}/messages`,message)
                .then((conversation) => {
                    commit(TYPES.CREATE, { message });
                    resolve({ conversation, message });
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
