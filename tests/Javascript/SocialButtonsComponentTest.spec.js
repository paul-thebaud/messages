import {mount} from 'vue-test-utils';
import expect from 'expect';
import SocialButtons from '../../resources/js/components/common/SocialButtons.vue';

describe('Social Buttons', () => {
    let component;

    beforeEach(() => {
        component = mount(SocialButtons);
    });

    it('contains google', () => {
        expect(component.html()).toContain('Google');
    });

    it('contains facebook', () => {
        expect(component.html()).toContain('Facebook');
    });
});
