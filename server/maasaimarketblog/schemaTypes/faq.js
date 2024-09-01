import {defineField, defineType} from 'sanity'

export default defineType({
    name: 'faq',
    title: 'FAQ',
    type: 'document',
    fields: [
        defineField({
            name: 'question',
            title: 'Question',
            type: 'string',
            description: 'The frequently asked question.',
            validation: (Rule) => Rule.required().min(10).warning('A question is required.'),
        }),
        defineField({
            name: 'answer',
            title: 'Answer',
            type: 'text',
            description: 'The answer to the question.',
            validation: (Rule) => Rule.required().min(20).warning('An answer is required.'),
        }),
        defineField({
            name: 'sortOrder',
            title: 'Sort Order',
            type: 'number',
            description: 'Custom sort order. Lower numbers appear first.',
            initialValue: 0,
        }),
    ],
    orderings: [
        {
            title: 'Sort by Custom Order',
            name: 'customOrder',
            by: [{field: 'sortOrder', direction: 'asc'}],
        },
    ],
})
