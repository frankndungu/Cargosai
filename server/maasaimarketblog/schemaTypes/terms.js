import {defineField, defineType} from 'sanity'

export default defineType({
    name: 'termsOfService',
    title: 'Terms Of Service',
    type: 'document',
    fields: [
        defineField({
            name: 'title',
            title: 'Title',
            type: 'string',
            validation: (Rule) => Rule.required(),
        }),
        defineField({
            name: 'content',
            title: 'Content',
            type: 'array',
            of: [{type: 'block'}],
        }),
        defineField({
            name: 'releaseDate',
            title: 'Release Date',
            type: 'datetime',
            validation: (Rule) => Rule.required(),
        }),
    ],
})
