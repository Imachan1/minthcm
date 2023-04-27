import cloneDeep from 'lodash.clonedeep'

export default function resetStore({ store }) {
  const initialState = cloneDeep(store.$state)
  console.log('initialState', store.$state)
  store.$reset = () => store.$patch(cloneDeep(initialState))
}
