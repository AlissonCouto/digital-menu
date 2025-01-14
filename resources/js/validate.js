jQuery(function($){

    function validateData(data, rules) {
        const errors = {};

        $('.field-error').html('');

        for (const field in rules) {
            const fieldRules = rules[field];

            for (const rule of fieldRules.split('|')) {
                const [ruleName, ruleParams] = rule.split(':');
                switch (ruleName) {
                    case 'required':
                        if (!data[field]) {
                            errors[field] = `O campo ${field} é obrigatório.`;
                        }
                        break;

                    case 'strong_password':
                        // Verifica se a senha atende aos critérios de segurança
                        const uppercaseRegex = /[A-Z]/;
                        const lowercaseRegex = /[a-z]/;
                        const numberRegex = /[0-9]/;
                        const specialCharsRegex = /[^\w]/;
                        const minChars = 8;

                        const hasUppercase = uppercaseRegex.test(data[field]);
                        const hasLowercase = lowercaseRegex.test(data[field]);
                        const hasNumber = numberRegex.test(data[field]);
                        const hasSpecialChars = specialCharsRegex.test(data[field]);
                        const hasMinChars = data[field].length >= minChars;

                        if (!(hasUppercase && hasLowercase && hasNumber && hasSpecialChars && hasMinChars)) {
                            errors[field] = `A senha no campo ${field} não atende aos critérios de segurança.`;
                        }
                        break;

                    case 'same':
                        // Verifica se o campo é igual ao campo especificado em ruleParams
                        if (data[field] !== data[ruleParams]) {
                            errors[field] = `O campo ${field} deve ser igual ao campo ${ruleParams}.`;
                        }
                        break;

                    case 'string':
                        if (data[field] && typeof data[field] !== 'string') {
                            errors[field] = `O campo ${field} deve ser uma string.`;
                        }
                        break;
                    case 'min':
                        if (data[field] && data[field].length < ruleParams) {
                            errors[field] = `O campo ${field} deve ter no mínimo ${ruleParams} caracteres.`;
                        }
                        break;
                    case 'max':
                        if (data[field] && data[field].length > ruleParams) {
                            errors[field] = `O campo ${field} deve ter no máximo ${ruleParams} caracteres.`;
                        }
                        break;
                    case 'nullable':
                        // Verifica se o campo é nulo ou indefinido
                        if (data[field] !== null && typeof data[field] !== 'undefined') {
                            break;
                        }
                        // Se o campo é nulo, pula para a próxima regra
                        continue;
                    case 'date':
                        if (data[field] && !/^\d{4}-\d{2}-\d{2}$/.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de data válido (YYYY-MM-DD).`;
                        }
                        break;
                    case 'date_format':
                        const dateFormat = ruleParams.toUpperCase();
                        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;

                        if (data[field] && !dateRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de data válido (${dateFormat}).`;
                        }
                        break;
                    case 'before_or_equal':
                        const limitDate = new Date();
                        limitDate.setFullYear(limitDate.getFullYear() - 18);
                        const fieldValue = new Date(data[field]);
                        if (data[field] && fieldValue > limitDate) {
                            errors[field] = `O campo ${field} deve ser uma data anterior ou igual a ${limitDate.toISOString().split('T')[0]}.`;
                        }
                        break;
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (data[field] && !emailRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve ser um email válido.`;
                        }
                        break;
                    case 'cpf':
                        const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
                        if (data[field] && !cpfRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de CPF válido (XXX.XXX.XXX-XX).`;
                        }
                        break;
                    case 'cnpj':
                        const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
                        if (data[field] && !cnpjRegex.test(data[field])) {
                            errors[field] = `O campo ${field} deve estar no formato de CNPJ válido (XX.XXX.XXX/XXXX-XX).`;
                        }
                        break;
                    case 'unique':
                        if (field === 'email') {
                            $.ajax({
                                beforeSend: function () {
                                    //selectTamanho.children("option:first").text("Aguarde...");
                                },
                                type: 'get',
                                dataType: 'json',
                                url: '/kaiejoias/email-unique',
                                data: {
                                    email: data[field],
                                },
                                success: function (response) {
                                    if (response && response.success === false) {
                                        errors[field] = `O campo ${field} já está cadastrado.`;
                                    }
                                },
                                async: false, // Define a requisição AJAX como síncrona
                            });
                        }

                        if (field == 'cpf') {
                            $.ajax({
                                beforeSend: function () {
                                    //selectTamanho.children("option:first").text("Aguarde...");
                                },
                                type: 'get',
                                dataType: 'json',
                                url: '/kaiejoias/cpf-unique',
                                data: {
                                    cpf: data[field],
                                },
                                success: function (response) {
                                    if (response && response.success === false) {
                                        errors[field] = `O campo ${field} já está cadastrado.`;
                                    }
                                },
                                async: false, // Define a requisição AJAX como síncrona
                            });
                        }
                        break;

                    case 'numeric':
                        if (data[field] && isNaN(data[field])) {
                            errors[field] = `O campo ${field} deve ser um valor numérico.`;
                        }
                        break;

                    case 'digits_between':
                        const [minDigits, maxDigits] = ruleParams.split(',');
                        if (data[field] && (data[field].length < minDigits || data[field].length > maxDigits)) {
                            errors[field] = `O campo ${field} deve ter entre ${minDigits} e ${maxDigits} dígitos.`;
                        }
                        break;

                    case 'digits':
                        if (data[field] && data[field].length !== parseInt(ruleParams)) {
                            errors[field] = `O campo ${field} deve ter exatamente ${ruleParams} dígitos.`;
                        }
                        break;

                    case 'exists':
                        let dataQuery = '';
                        if (field === 'inputCity') {
                            dataQuery = {
                                id: data[field],
                                entity: 'city'
                            };
                        }

                        if (field === 'inputState') {
                            dataQuery = {
                                uf: data[field],
                                entity: 'state'
                            };
                        }

                        $.ajax({
                            beforeSend: function () {
                                //selectTamanho.children("option:first").text("Aguarde...");
                            },
                            type: 'get',
                            dataType: 'json',
                            url: '/rule-exists',
                            data: dataQuery,
                            success: function (response) {
                                if (response && response.success === false) {
                                    errors[field] = `O campo ${field} é inválido.`;
                                }
                            },
                            async: false, // Define a requisição AJAX como síncrona
                        });
                        break;
                }
            }
        }

        return errors;
    }

});