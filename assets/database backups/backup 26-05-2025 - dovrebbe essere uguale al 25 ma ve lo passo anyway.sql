PGDMP  3    8                }           postgres    17.4    17.4 4    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false                        0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false                       1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false                       0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4866            �            1255    25436    update_snip_likes()    FUNCTION       CREATE FUNCTION public.update_snip_likes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE snips
    SET challenge_points = (
        SELECT COUNT(*) FROM user_snip_likes WHERE snip_id = NEW.snip_id
    )
    WHERE id = NEW.snip_id;
    RETURN NULL;
END;
$$;
 *   DROP FUNCTION public.update_snip_likes();
       public               postgres    false            �            1259    25647 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    25653    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    25659    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218                       0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    25660    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    25666    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220                       0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    25667    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    25676    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222                       0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    25677    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
 #   DROP VIEW public.snips_with_likes;
       public       v       postgres    false            �            1259    25681    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    25684    users    TABLE     f  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[],
    remember_token text
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    25690    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    226                       0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    227            >           2604    25691    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            @           2604    25692 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            B           2604    25693    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            G           2604    25694    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226            �          0    25647 
   challenges 
   TABLE DATA           ]   COPY public.challenges (name, description, date_start, date_end, winners, image) FROM stdin;
    public               postgres    false    217   D       �          0    25653    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    218   !E       �          0    25660    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   >E       �          0    25667    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   �E       �          0    25681    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    225   7I       �          0    25684    users 
   TABLE DATA           �   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following, remember_token) FROM stdin;
    public               postgres    false    226   `I                  0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    219            	           0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 24, true);
          public               postgres    false    221            
           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 46, true);
          public               postgres    false    223                       0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 6, true);
          public               postgres    false    227            J           2606    25696    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            L           2606    25698    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            N           2606    25700    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            P           2606    25702    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            R           2606    25704 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    225    225            T           2606    25706    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    226            V           2606    25708    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    226            X           2606    25710    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    226            �           2618    25680    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
 SELECT s.id,
    s.creator,
    s.views,
    s.created_at,
    s.description,
    s.element_type,
    s.tags,
    s.file_location,
    s.likes,
    s.saved,
    s.variation_of,
    s.challenge_of,
    count(usl.snip_id) AS challenge_likes
   FROM (public.snips s
     LEFT JOIN public.user_snip_likes usl ON ((s.id = usl.snip_id)))
  GROUP BY s.id;
 �  CREATE OR REPLACE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
       public               postgres    false    222    4688    225    222    222    222    222    222    222    222    222    222    222    222    224            Y           2606    25712    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    4688    222    218            Z           2606    25717    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    218    226    4696            [           2606    25722    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    220    4682    217            \           2606    25727    drafts drafts_creator_fkey    FK CONSTRAINT        ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    220    226    4696            ]           2606    25732    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    226    222    4696            _           2606    25737 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    225    4688    222            `           2606    25742 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    225    226    4694            ^           2606    25747    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    222    217    4682            �     x��ӱn�0���� LBیE�"�#Re�ɸ�GZ���Z�� ��ý�=�c.��Kx��D��4drB�
�@8�t�[��%�C�r��c)ӊ�$�J�E�vC�U�:�yl�x;�{?<x�{p}>�`��sQr��1H&Id��gV�D%Б����Z�Q�bsn��*�O{�'X��R�y��Kէ+/g�n��e�,�����u�_���˚Y躗6n��8֥�����fB��9��c���:�-��)n�����m��G�      �      x������ � �      �   c   x��K@0 ���� a�����"#>m	#~qw^� �	`�D ��q^PRn4��"���6�w��|h���</t�9v֝��f���_աR��       �   v  x��Tk��6���
�w��V+�L�U��n�DU+��2��g�f������G2�iW+���9��{�"#Z����ͼ[ۻ��N�F�ZaF~�t-�Oi�[�3Ѧ����
s%+nD�����z{���!*}�gQ
#��'�c>��>�k�l8�|�s����b;��г6���/8{q2�^�V��l]Ն��y&UNx:���ަ����0�07v���,�	���M!��ҤNa�)�r��ZB�@�Z�R	�,B/��N��k�Ⱦ� ;ÛX�rDO����1yV�^�0�?%�=l�1�bǳ";r��Fc>�sl �[�5:��p�ɣh�:iq�M��Z�TjXx��>t*�*w�ȯ��DC~9եnD3�^�L��Xg�D&ő�xg�Rl[��]�h�if������N)M��.q[J �0��ɟ��&�t�2�����7UwfD�����wX����lйԳ��qC���G�$�2�s�Q	-��ܕ:}"��(�kH|�:�Xo�B�<��ʓ(�"��YYPﵝ�a���,Jh��b8�+�c�J(sу���t��*��3�	m<�=�h�]����fV���?�����k����t?$��v��,np���R/��Xv�v��"/�ˇ|���ɩ���#p���+1�|��0��)�㭮p�M3;�ݘ�燶���Xhp��n0�-�������y���������d^M�<�8HQO���;��7%v�r��F0[��a�ǘ�|��s��{J6)t���-G�\T�Pd�A\� �҈/+�®������Nh���<��R�(��C�؛L��."�Q���z.�1�����|?�*D�j�g|n���[���ɝn�?wf�B�����-fq�	b��Ձ�X���_K��%      �      x�3�42�2��Ʀ\1z\\\ ��      �   �  x���[��0���W���&Ł}Zt��Ae�L�)P��ʥ�h濏�f�Ɲl�9iN�&��sO��I�3H%�A�����A
P¸8�z|��s�=7/yw;�T�.ǉ�mk���K��EeZ����!a�0|�lQG+����&[���"m����ߨ7�wtT�*�$'��7F�B/,���k���}sM.�P6k8y�i��x�G�J}��]������А^������*��4�=��E6K�]Cv}2_��!=��O�X��̢��*�f� ��
��_�ޯu>�����.��0��3)��:0�p��e�屌���"6˫�v$ض _|~t��O랿���k��%KU�k�ҁ�b(�Wf�|������6�Bi?����_���/PI^����Ո�(F0�<�{Z�PM7��s&E�������2V�>�N�~�Ci�}�j@�0큢��� B0]�iՆ^�C:G{��
B|������#�Ax)��2P��vޘN��qv&z     