import {defineField, defineType} from 'sanity'

export default defineType({
    name: 'samples',
    title: 'Samples',
    type: 'document',
    fields: [
        defineField({
            name: 'name',
            title: 'Sample Name',
            type: 'string',
        }),
    ],
})
